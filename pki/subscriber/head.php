<?php
/**
 * KISTI CA Subscriber Portal - Header
 * Modern iOS-style mobile-first design with Tailwind CSS
 */

// Base path 자동 감지
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = '';
$pkiBasePath = '';

// subscriber 디렉토리 위치 찾기
if (strpos($scriptPath, '/kisti-ca/pki/subscriber') !== false) {
  $basePath = '/kisti-ca';
  $pkiBasePath = '/kisti-ca/pki/subscriber';
} elseif (strpos($scriptPath, '/pki/subscriber') !== false) {
  $basePath = preg_replace('#/pki/subscriber.*$#', '', $scriptPath);
  $pkiBasePath = $basePath . '/pki/subscriber';
} else {
  $pkiBasePath = '/subscriber';
}

// assets 경로
$assetsPath = $basePath . '/assets';

// 현재 페이지 경로 (active 메뉴 표시용)
$currentPage = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

/**
 * ParagraphTitle 함수 (호환성 유지 + 새 디자인)
 */
function ParagraphTitle($title, $icon = null) {
  $iconHtml = $icon ? "<span class=\"material-symbols-outlined text-primary\">{$icon}</span>" : '';
  print<<<EOS
<div class="page-header flex items-center gap-3 pb-4 mb-6 border-b-2 border-primary">
  {$iconHtml}
  <h1 class="text-xl font-bold text-slate-900 dark:text-white">{$title}</h1>
</div>
EOS;
}

/**
 * StatusBadge 함수 - 상태 배지 생성
 */
function StatusBadge($type, $label) {
  $colors = array(
    'success' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
    'pending' => 'bg-blue-100 dark:bg-primary/20 text-primary',
    'failed' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
    'warning' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400'
  );
  $colorClass = isset($colors[$type]) ? $colors[$type] : $colors['pending'];
  return "<span class=\"{$colorClass} text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider\">{$label}</span>";
}

/**
 * 네비게이션 아이템 활성화 체크
 */
function isNavActive($href, $currentPage) {
  if ($href === '/' || $href === '/index.php' || $href === 'index.php') {
    return preg_match('#/subscriber/?$#', $currentPage) || preg_match('#/subscriber/index\.php#', $currentPage);
  }
  return strpos($currentPage, $href) !== false;
}

?>
<!DOCTYPE html>
<html lang="ko" class="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>KISTI CA - Subscriber Portal</title>

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
            'primary': '#135bec',
            'background-light': '#f6f6f8',
            'background-dark': '#101622',
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
      font-family: 'Inter', sans-serif;
    }

    /* iOS Safe Area */
    .pb-safe {
      padding-bottom: env(safe-area-inset-bottom, 0);
    }

    /* 폼 스타일 (레거시 호환) */
    table.data { width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 0.75rem; overflow: hidden; }
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
    div.message1 { padding: 1rem; color: #166534; background: #f0fdf4; border-radius: 0.75rem; margin: 0.75rem 0; border-left: 4px solid #22c55e; }
    div.message2 { padding: 1rem; color: #991b1b; background: #fef2f2; border-radius: 0.75rem; margin: 0.75rem 0; border-left: 4px solid #ef4444; }
    div.message3 { padding: 1rem; color: #1e40af; background: #eff6ff; border-radius: 0.75rem; margin: 0.75rem 0; border-left: 4px solid #135bec; }
    input.button, input[type="submit"], input[type="button"] {
      background: #135bec;
      color: white;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 0.75rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.15s;
      font-size: 0.875rem;
    }
    input.button:hover, input[type="submit"]:hover, input[type="button"]:hover {
      background: #0d4abf;
      transform: scale(0.98);
    }
    label.important { font-size: 1.1em; font-weight: 600; color: #0f172a; }
    input[type="text"], input[type="email"], input[type="password"], select, textarea {
      padding: 0.75rem 1rem;
      border: 1px solid #e2e8f0;
      border-radius: 0.75rem;
      font-size: 0.875rem;
      transition: border-color 0.15s, box-shadow 0.15s;
      background: white;
    }
    input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #135bec;
      box-shadow: 0 0 0 3px rgba(19, 91, 236, 0.1);
    }

    /* Dark mode overrides */
    .dark table.data th { background: #1e293b; color: #94a3b8; border-color: #334155; }
    .dark table.data td { background: #0f172a; border-color: #334155; color: #e2e8f0; }
    .dark input[type="text"], .dark input[type="email"], .dark input[type="password"], .dark select, .dark textarea {
      background: #1e293b;
      border-color: #334155;
      color: #e2e8f0;
    }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white antialiased min-h-screen">

<div class="flex min-h-screen">

  <!-- PC 사이드바 (md 이상에서만 표시) -->
  <aside class="hidden md:flex flex-col w-56 bg-white dark:bg-[#192233] border-r border-slate-200 dark:border-slate-800 fixed h-full">
    <!-- 로고 -->
    <div class="p-4 border-b border-slate-200 dark:border-slate-700">
      <a href="<?=$pkiBasePath?>/" class="flex items-center gap-2">
        <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
          <span class="material-symbols-outlined text-white">shield_lock</span>
        </div>
        <div>
          <p class="font-bold text-slate-900 dark:text-white">KISTI Grid CA</p>
          <p class="text-xs text-slate-500 dark:text-slate-400">Subscriber Portal</p>
        </div>
      </a>
    </div>

    <!-- 네비게이션 -->
    <nav class="flex-1 overflow-y-auto p-2">
      <a href="<?=$pkiBasePath?>/" class="nav-item <?=isNavActive('index.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">dashboard</span>
        Dashboard
      </a>
      <a href="<?=$pkiBasePath?>/csr_v3.php" class="nav-item <?=isNavActive('csr_v3.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">receipt_long</span>
        My CSRs
      </a>
      <a href="<?=$pkiBasePath?>/cert_v3.php" class="nav-item <?=isNavActive('cert_v3.php', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">verified</span>
        My Certificates
      </a>

      <div class="nav-section-label">Request</div>
      <a href="<?=$pkiBasePath?>/request_user_cert_v3.php" class="nav-item <?=isNavActive('request_user_cert', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">person_add</span>
        User Certificate
      </a>
      <a href="<?=$pkiBasePath?>/request_host_cert_v3.php" class="nav-item <?=isNavActive('request_host_cert', $currentPage) ? 'active' : ''?>">
        <span class="material-symbols-outlined">dns</span>
        Host Certificate
      </a>
    </nav>

    <!-- 사이드바 하단 -->
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      <p class="text-xs text-slate-400 dark:text-slate-500 text-center">Subscriber Portal v3.0</p>
    </div>
  </aside>

  <!-- 메인 영역 -->
  <div class="flex-1 md:ml-56">

    <!-- 앱 컨테이너 (모바일: 480px 중앙, PC: 전체 너비) -->
    <div class="relative flex h-full min-h-screen w-full flex-col
                max-w-[480px] md:max-w-none
                mx-auto md:mx-0
                bg-white dark:bg-background-dark
                border-x md:border-x-0 border-slate-200 dark:border-slate-800
                shadow-2xl md:shadow-none">

      <!-- 모바일 TopAppBar (모바일에서만 표시) -->
      <header class="md:hidden sticky top-0 z-50 flex flex-col bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md">
        <div class="flex items-center p-4 pb-2 justify-between border-b border-slate-200 dark:border-slate-800">
          <button onclick="history.back()" class="text-slate-700 dark:text-slate-300 flex size-10 items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700 rounded-full transition-colors">
            <span class="material-symbols-outlined">arrow_back_ios</span>
          </button>
          <div class="flex items-center gap-2">
            <div class="bg-primary/10 p-1.5 rounded-lg text-primary">
              <span class="material-symbols-outlined text-lg">shield_lock</span>
            </div>
            <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight">KISTI Grid CA</h2>
          </div>
          <!-- 오른쪽 공간 유지용 (중앙 정렬) -->
          <div class="size-10"></div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto p-4 md:p-6 pb-24 md:pb-6">
