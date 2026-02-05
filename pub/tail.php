<?php
/**
 * KISTI CA Public Portal - Footer
 * Tailwind CSS 기반 반응형 레이아웃
 */

// Base path 재사용 (head.php에서 설정된 경우)
if (!isset($basePath)) {
  $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
  $basePath = '';
  if (strpos($scriptPath, '/kisti-ca/pub') !== false) {
    $basePath = '/kisti-ca/pub';
  } elseif (strpos($scriptPath, '/pub') !== false) {
    $basePath = preg_replace('#/pub.*$#', '/pub', $scriptPath);
  }
}

// assets 경로
if (!isset($assetsPath)) {
  $assetsPath = str_replace('/pub', '/assets', $basePath);
  if (empty($assetsPath)) {
    $assetsPath = '/assets';
  }
}

// 현재 페이지
if (!isset($currentPage)) {
  /** $currentPage = $_SERVER['REQUEST_URI'] ?? ''; latest php version**/ 
  $currentPage = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';  // PHP 5.6 호환성
}
?>

    </main>
    <!-- /콘텐츠 영역 -->

    <!-- 데스크톱 푸터 -->
    <footer class="hidden md:block bg-white border-t border-slate-200 mt-auto">
      <div class="p-6 text-center text-sm text-slate-500 leading-relaxed">
        <p class="font-semibold text-slate-700 mb-2">KISTI Certification Authority</p>
        <p>Global Science experimental Data hub Center</p>
        <p>National Institute of Supercomputing and Networking</p>
        <p>Korea Institute of Science and Technology Information</p>
        <p class="mt-2 text-slate-400">245 Daehak-ro, Yuseong-gu 34141 Daejeon, Republic of Korea</p>
      </div>
    </footer>

  </div>
  <!-- /메인 영역 -->

</div>
<!-- /flex container -->

<!-- 모바일 하단 탭 바 -->
<footer class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 pb-safe z-30">
  <div class="flex justify-around items-center pt-2 pb-1">
    <a href="<?=$basePath?>/" class="bottom-tab <?=(preg_match('#(/pub/?$|/pub/index\.php)#', $currentPage) || $currentPage === '/') ? 'active' : ''?>">
      <span class="material-symbols-outlined">home</span>
      <span>Home</span>
    </a>
    <a href="<?=$basePath?>/certs/cacerts.php" class="bottom-tab <?=strpos($currentPage, '/certs/') !== false ? 'active' : ''?>">
      <span class="material-symbols-outlined">workspace_premium</span>
      <span>Certs</span>
    </a>
    <a href="<?=$basePath?>/cps/cps.php" class="bottom-tab <?=strpos($currentPage, '/cps/') !== false ? 'active' : ''?>">
      <span class="material-symbols-outlined">description</span>
      <span>CPS</span>
    </a>
    <a href="<?=$basePath?>/request/certificte_request.php" class="bottom-tab <?=strpos($currentPage, '/request/') !== false ? 'active' : ''?>">
      <span class="material-symbols-outlined">add_circle</span>
      <span>Request</span>
    </a>
    <a href="<?=$basePath?>/issued_v3/index.php" class="bottom-tab <?=strpos($currentPage, '/issued') !== false ? 'active' : ''?>">
      <span class="material-symbols-outlined">verified</span>
      <span>Issued</span>
    </a>
    <a href="<?=$basePath?>/contact.php" class="bottom-tab <?=strpos($currentPage, '/contact') !== false ? 'active' : ''?>">
      <span class="material-symbols-outlined">contact_support</span>
      <span>Contact</span>
    </a>
  </div>
  <!-- iOS Safe Area -->
  <div class="h-safe"></div>
</footer>

<!-- JavaScript -->
<script src="<?=$assetsPath?>/js/menu.js"></script>

</body>
</html>
