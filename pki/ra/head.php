<?php
/**
 * KISTI CA - RA Admin Portal Header
 * Tailwind CSS 기반 반응형 레이아웃
 */
header('Content-Type:text/html; charset=UTF-8');

// Base path 자동 감지
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = '';
$pkiBasePath = '';

// ra 디렉토리 위치 찾기
if (strpos($scriptPath, '/kisti-ca/pki/ra') !== false) {
  $basePath = '/kisti-ca';
  $pkiBasePath = '/kisti-ca/pki/ra';
} elseif (strpos($scriptPath, '/pki/ra') !== false) {
  $basePath = preg_replace('#/pki/ra.*$#', '', $scriptPath);
  $pkiBasePath = $basePath . '/pki/ra';
} else {
  $pkiBasePath = '/ra';
}

// assets 경로
$assetsPath = $basePath . '/assets';

// 현재 페이지 경로 (PHP 5.4 호환)
$currentPage = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

/**
 * ParagraphTitle 함수 (호환성 유지 + 새 디자인)
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
  if ($href === '/' || $href === '/index.php' || $href === 'index.php') {
    return preg_match('#/ra/?$#', $currentPage) || preg_match('#/ra/index\.php#', $currentPage);
  }
  return strpos($currentPage, $href) !== false;
}

?>
<!DOCTYPE html>
<html lang="ko" class="light">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KISTI CA - RA Admin Portal</title>

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
    /* 폼 스타일 (레거시 호환) */
    table.data { width: 100%; border-collapse: separate; border-spacing: 0; }
    table.data th {
      background: #f8fafc;
      padding: 0.75rem 1rem;
      text-align: right;
      font-weight: 500;
      color: #64748b;
      font-size: 0.875rem;
      border-bottom: 1px solid #e2e8f0;
      width: 150px;
    }
    table.data td {
      background: #fff;
      padding: 0.75rem 1rem;
      text-align: left;
      border-bottom: 1px solid #e2e8f0;
    }
    table.data td.c { text-align: center; font-weight: 600; }
    span.red { color: #ef4444; }
    div.message1 { padding: 0.75rem; color: #22c55e; background: #f0fdf4; border-radius: 0.5rem; margin: 0.5rem 0; }
    div.message2 { padding: 0.75rem; color: #ef4444; background: #fef2f2; border-radius: 0.5rem; margin: 0.5rem 0; }
    div.message3 { padding: 0.75rem; color: #8b5cf6; background: #f5f3ff; border-radius: 0.5rem; margin: 0.5rem 0; }
    input.button, input[type="submit"], input[type="button"] {
      background: #137fec;
      color: white;
      padding: 0.5rem 1.5rem;
      border: none;
      border-radius: 0.5rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.15s;
    }
    input.button:hover, input[type="submit"]:hover, input[type="button"]:hover {
      background: #0d6ecc;
    }
    label.important { font-size: 1.1em; font-weight: 600; color: #0f172a; }
    input[type="text"], input[type="email"], input[type="password"], select, textarea {
      padding: 0.5rem 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 0.375rem;
      font-size: 0.875rem;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #137fec;
      box-shadow: 0 0 0 3px rgba(19, 127, 236, 0.1);
    }
  </style>
</head>
<body class="bg-background-light font-display text-slate-900 antialiased">

<div class="flex min-h-screen">

  <!-- 데스크톱 사이드바 -->
  <aside class="hidden md:flex flex-col w-56 bg-white border-r border-slate-200 fixed h-full">
    <!-- 로고 -->
    <div class="p-4 border-b border-slate-200">
      <a href="<?=$pkiBasePath?>/" class="flex items-center gap-2">
        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center">
          <span class="material-symbols-outlined text-white">assignment_ind</span>
        </div>
        <div>
          <p class="font-bold text-slate-900">RA Admin</p>
          <p class="text-xs text-slate-500">Registration Authority</p>
        </div>
      </a>
    </div>

    <!-- 네비게이션 -->
    <nav class="flex-1 overflow-y-auto p-2">
      <a href="<?=$pkiBasePath?>/" class="nav-item <?=isNavActive('index.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">home</span>
        RA Home
      </a>

      <div class="nav-section-label">Management (v3.0)</div>
      <a href="<?=$pkiBasePath?>/newsubscriber_v3.php" class="nav-item <?=isNavActive('newsubscriber_v3.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">person_add</span>
        New Subscriber
      </a>
      <a href="<?=$pkiBasePath?>/subscribers_v3.php" class="nav-item <?=isNavActive('subscribers_v3.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">group</span>
        Subscribers
      </a>

      <div class="nav-section-label">External Links</div>
      <a href="http://ca.gridcenter.or.kr/" target="_blank" class="nav-item">
        <span class="material-symbols-outlined">open_in_new</span>
        KISTI CA Home
      </a>
    </nav>

    <!-- 사이드바 하단 -->
    <div class="p-4 border-t border-slate-200">
      <p class="text-xs text-slate-400 text-center">KISTI Grid CA v3.0</p>
    </div>
  </aside>

  <!-- 메인 영역 -->
  <div class="flex-1 md:ml-56">

    <!-- 모바일 헤더 -->
    <header class="md:hidden sticky top-0 z-50 bg-background-light/80 backdrop-blur-md border-b border-slate-200">
      <div class="flex items-center justify-center p-4">
        <div class="flex items-center gap-2">
          <div class="bg-amber-500/10 p-1.5 rounded-lg text-amber-600">
            <span class="material-symbols-outlined text-lg">assignment_ind</span>
          </div>
          <h2 class="text-amber-600 font-bold text-lg">RA Admin</h2>
        </div>
      </div>
    </header>

    <!-- 콘텐츠 영역 -->
    <main class="site-content p-4 md:p-6 pb-24 md:pb-6">
