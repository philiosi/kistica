<?php
/**
 * KISTI CA - CA Admin Portal Footer
 * Tailwind CSS 기반 반응형 레이아웃
 */

// Base path 재사용 (head.php에서 설정된 경우)
if (!isset($pkiBasePath)) {
  $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
  $basePath = '';
  $pkiBasePath = '';

  if (strpos($scriptPath, '/kisti-ca/pki/ca') !== false) {
    $basePath = '/kisti-ca';
    $pkiBasePath = '/kisti-ca/pki/ca';
  } elseif (strpos($scriptPath, '/pki/ca') !== false) {
    $basePath = preg_replace('#/pki/ca.*$#', '', $scriptPath);
    $pkiBasePath = $basePath . '/pki/ca';
  } else {
    $pkiBasePath = '/ca';
  }
}

// assets 경로
if (!isset($assetsPath)) {
  $assetsPath = (isset($basePath) ? $basePath : '') . '/assets';
}

// 현재 페이지 (PHP 5.4 호환)
if (!isset($currentPage)) {
  $currentPage = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
}
?>

    </main>
    <!-- /콘텐츠 영역 -->

    <!-- 데스크톱 푸터 -->
    <footer class="hidden md:block bg-white border-t border-slate-200 mt-auto">
      <div class="p-6 text-center text-sm text-slate-500">
        <p class="font-semibold text-slate-700">KISTI Certification Authority</p>
        <p class="mt-1 text-slate-400">CA Admin Portal v3.0</p>
      </div>
    </footer>

  </div>
  <!-- /메인 영역 -->

</div>
<!-- /flex container -->

<!-- 모바일 하단 탭 바 -->
<footer class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 pb-safe z-30">
  <div class="flex justify-around items-center pt-2 pb-1">
    <a href="<?=$pkiBasePath?>/" class="bottom-tab <?=preg_match('#/ca/?$#', $currentPage) || preg_match('#/ca/index\.php#', $currentPage) ? 'active' : ''?>">
      <span class="material-symbols-outlined">home</span>
      <span>Home</span>
    </a>
    <a href="<?=$pkiBasePath?>/subscribers_v3.php" class="bottom-tab <?=strpos($currentPage, 'subscribers') !== false ? 'active' : ''?>">
      <span class="material-symbols-outlined">group</span>
      <span>Users</span>
    </a>
    <a href="<?=$pkiBasePath?>/csr_v3.php" class="bottom-tab <?=strpos($currentPage, 'csr_v3.php') !== false ? 'active' : ''?>">
      <span class="material-symbols-outlined">pending_actions</span>
      <span>CSR</span>
    </a>
    <a href="<?=$pkiBasePath?>/cert_v3.php" class="bottom-tab <?=strpos($currentPage, 'cert_v3.php') !== false ? 'active' : ''?>">
      <span class="material-symbols-outlined">verified</span>
      <span>Certs</span>
    </a>
  </div>
  <!-- iOS Safe Area -->
  <div class="h-safe"></div>
</footer>

<!-- JavaScript -->
<script src="<?=$assetsPath?>/js/menu.js"></script>

</body>
</html>
