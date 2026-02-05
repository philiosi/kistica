<?php
/**
 * KISTI CA Public Portal - Header
 * Tailwind CSS 기반 반응형 레이아웃
 */

// Base path 자동 감지 (로컬/프로덕션 호환)
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = '';

// pub 디렉토리 위치 찾기
if (strpos($scriptPath, '/kisti-ca/pub') !== false) {
  $basePath = '/kisti-ca/pub';
} elseif (strpos($scriptPath, '/pub') !== false) {
  $basePath = preg_replace('#/pub.*$#', '/pub', $scriptPath);
}

// assets 경로 (pub 상위 디렉토리)
$assetsPath = str_replace('/pub', '/assets', $basePath);
if (empty($assetsPath)) {
  $assetsPath = '/assets';
}

// 현재 페이지 경로 (active 메뉴 표시용)
/** $currentPage = $_SERVER['REQUEST_URI'] ?? ''; latest php version**/ 
$currentPage = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';  // PHP 5.6 호환성

/**
 * ParagraphTitle 함수 (호환성 유지)
 */
function ParagraphTitle($title, $icon = null) {
  $iconHtml = $icon ? "<span class=\"material-symbols-outlined text-primary\">{$icon}</span>" : '';
  print<<<EOS
<div class="page-header">
  {$iconHtml}
  <h1>{$title}</h1>
</div>
EOS;
}

/**
 * 네비게이션 아이템 활성화 체크
 */
function isNavActive($href, $currentPage) {
  if ($href === '/' || $href === '/index.php') {
    return ($currentPage === '/' || $currentPage === '/index.php' || preg_match('#/pub/?$#', $currentPage));
  }
  return strpos($currentPage, $href) !== false;
}

?>
<!DOCTYPE html>
<html lang="ko" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KISTI Certification Authority</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  <!-- Tailwind Config -->
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            'primary': '#137fec',
            'background-light': '#f6f7f8',
            'background-dark': '#101922',
          },
          fontFamily: {
            'display': ['Inter', 'sans-serif']
          },
          borderRadius: {
            'DEFAULT': '0.25rem',
            'lg': '0.5rem',
            'xl': '0.75rem',
            'full': '9999px'
          },
        },
      },
    }
  </script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?=$assetsPath?>/css/main.css">

  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    body {
      -webkit-tap-highlight-color: transparent;
    }
    /* 모바일 슬라이드 메뉴 */
    #mobile-menu.active {
      transform: translateX(0);
    }
    #mobile-menu-overlay.active {
      opacity: 1;
      pointer-events: auto;
    }
  </style>
</head>
<body class="bg-background-light font-display text-slate-900 antialiased">

<div class="flex min-h-screen">

  <!-- 데스크톱 사이드바 -->
  <aside class="hidden md:flex flex-col w-56 bg-white border-r border-slate-200 fixed h-full">
    <!-- 로고 -->
    <div class="p-4 border-b border-slate-200">
      <a href="<?=$basePath?>/" class="flex items-center gap-2">
        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
          <span class="material-symbols-outlined text-white">shield</span>
        </div>
        <div>
          <p class="font-bold text-slate-900">KISTI CA</p>
          <p class="text-xs text-slate-500">Certificate Authority</p>
        </div>
      </a>
    </div>

    <!-- 네비게이션 -->
    <nav class="flex-1 overflow-y-auto p-2">
      <a href="<?=$basePath?>/" class="nav-item <?=isNavActive('/', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">home</span>
        Home
      </a>
      <a href="<?=$basePath?>/certs/cacerts.php" class="nav-item <?=isNavActive('/certs/', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">workspace_premium</span>
        CA Certificates
      </a>

      <div class="nav-section-label">Documents</div>
      <a href="<?=$basePath?>/cps/cps.php" class="nav-item <?=isNavActive('/cps/cps.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">description</span>
        CP/CPS
      </a>
      <a href="<?=$basePath?>/cps/min_requirement.php" class="nav-item <?=isNavActive('/cps/min_requirement', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">checklist</span>
        Min. Requirements
      </a>

      <div class="nav-section-label">Services</div>
      <a href="<?=$basePath?>/request/certificte_request.php" class="nav-item <?=isNavActive('/request/', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">add_circle</span>
        Request Certs
      </a>
      <a href="<?=$basePath?>/issued_v3/index.php" class="nav-item <?=isNavActive('/issued_v3/', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">verified</span>
        Issued Certs
      </a>
      <a href="<?=$basePath?>/crl.php" class="nav-item <?=isNavActive('/crl.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">security</span>
        CRL
      </a>

      <div class="nav-section-label">Information</div>
      <a href="<?=$basePath?>/contact.php" class="nav-item <?=isNavActive('/contact.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">contact_support</span>
        Contact
      </a>
      <a href="<?=$basePath?>/sites.php" class="nav-item <?=isNavActive('/sites.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">link</span>
        Related Sites
      </a>
    </nav>

    <!-- 사이드바 하단 -->
    <div class="p-4 border-t border-slate-200">
      <p class="text-xs text-slate-400 text-center">KISTI Grid CA v3.0</p>
    </div>
  </aside>

  <!-- 메인 영역 -->
  <div class="flex-1 md:ml-56">

    <!-- 모바일 메뉴 오버레이 -->
    <div id="mobile-menu-overlay" class="md:hidden fixed inset-0 bg-black/50 z-40 opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <!-- 모바일 슬라이드 메뉴 -->
    <aside id="mobile-menu" class="md:hidden fixed top-0 left-0 h-full w-72 bg-white z-50 transform -translate-x-full transition-transform duration-300 shadow-xl">
      <!-- 메뉴 헤더 -->
      <div class="p-4 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-white">shield</span>
          </div>
          <div>
            <p class="font-bold text-slate-900">KISTI CA</p>
            <p class="text-xs text-slate-500">Certificate Authority</p>
          </div>
        </div>
        <button id="mobile-menu-close" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
          <span class="material-symbols-outlined text-slate-500">close</span>
        </button>
      </div>

      <!-- 메뉴 네비게이션 -->
      <nav class="flex-1 overflow-y-auto p-2">
        <a href="<?=$basePath?>/" class="nav-item <?=isNavActive('/', $currentPage) ? 'active' : ''?>">
          <span class="material-symbols-outlined">home</span>
          Home
        </a>
        <a href="<?=$basePath?>/certs/cacerts.php" class="nav-item <?=isNavActive('/certs/', $currentPage) ? 'active' : ''?>">
          <span class="material-symbols-outlined">workspace_premium</span>
          CA Certificates
        </a>

        <div class="nav-section-label">Documents</div>
        <a href="<?=$basePath?>/cps/cps.php" class="nav-item <?=isNavActive('/cps/cps.php', $currentPage) ? 'active' : ''?>">
          <span class="material-symbols-outlined">description</span>
          CP/CPS
        </a>
        <a href="<?=$basePath?>/cps/min_requirement.php" class="nav-item <?=isNavActive('/cps/min_requirement', $currentPage) ? 'active' : ''?>">
          <span class="material-symbols-outlined">checklist</span>
          Min. Requirements
        </a>

        <div class="nav-section-label">Services</div>
        <a href="<?=$basePath?>/request/certificte_request.php" class="nav-item <?=isNavActive('/request/', $currentPage) ? 'active' : ''?>">
          <span class="material-symbols-outlined">add_circle</span>
          Request Certs
        </a>
        <a href="<?=$basePath?>/issued_v3/index.php" class="nav-item <?=isNavActive('/issued_v3/', $currentPage) ? 'active' : ''?>">
          <span class="material-symbols-outlined">verified</span>
          Issued Certs
        </a>
        <a href="<?=$basePath?>/crl.php" class="nav-item <?=isNavActive('/crl.php', $currentPage) ? 'active' : ''?>">
          <span class="material-symbols-outlined">security</span>
          CRL
        </a>

        <div class="nav-section-label">Information</div>
        <a href="<?=$basePath?>/contact.php" class="nav-item <?=isNavActive('/contact.php', $currentPage) ? 'active' : ''?>">
          <span class="material-symbols-outlined">contact_support</span>
          Contact
        </a>
        <a href="<?=$basePath?>/sites.php" class="nav-item <?=isNavActive('/sites.php', $currentPage) ? 'active' : ''?>">
          <span class="material-symbols-outlined">link</span>
          Related Sites
        </a>
      </nav>

      <!-- 메뉴 하단 -->
      <div class="p-4 border-t border-slate-200">
        <p class="text-xs text-slate-400 text-center">KISTI Grid CA v3.0</p>
      </div>
    </aside>

    <!-- 모바일 헤더 -->
    <header class="md:hidden sticky top-0 z-30 bg-background-light/80 backdrop-blur-md border-b border-slate-200">
      <div class="flex items-center justify-between p-4">
        <!-- 햄버거 메뉴 버튼 -->
        <button id="mobile-menu-toggle" class="p-2 hover:bg-slate-200 rounded-lg transition-colors">
          <span class="material-symbols-outlined text-slate-700">menu</span>
        </button>
        <!-- 타이틀 -->
        <div class="flex items-center gap-2">
          <div class="bg-primary/10 p-1.5 rounded-lg text-primary">
            <span class="material-symbols-outlined text-lg">shield</span>
          </div>
          <h2 class="text-slate-900 font-bold text-lg">KISTI CA</h2>
        </div>
        <!-- 오른쪽 공간 (균형용) -->
        <div class="w-10"></div>
      </div>
    </header>

    <!-- 콘텐츠 영역 -->
    <main class="site-content p-4 md:p-6 pb-24 md:pb-6">
