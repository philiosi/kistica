<?php
/**
 * KISTI CA Subscriber Portal - Footer
 * Modern iOS-style mobile-first design with Tailwind CSS
 */

// Base path 재사용 (head.php에서 설정된 경우)
if (!isset($pkiBasePath)) {
  $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
  $basePath = '';
  $pkiBasePath = '';

  if (strpos($scriptPath, '/kisti-ca/pki/subscriber') !== false) {
    $basePath = '/kisti-ca';
    $pkiBasePath = '/kisti-ca/pki/subscriber';
  } elseif (strpos($scriptPath, '/pki/subscriber') !== false) {
    $basePath = preg_replace('#/pki/subscriber.*$#', '', $scriptPath);
    $pkiBasePath = $basePath . '/pki/subscriber';
  } else {
    $pkiBasePath = '/subscriber';
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
      <!-- /Main Content -->

      <!-- 모바일 Footer Info -->
      <footer class="md:hidden mt-auto px-4 pb-4 text-center">
        <p class="text-slate-400 dark:text-slate-600 text-xs font-medium uppercase tracking-widest">KISTI Grid CA</p>
        <p class="text-slate-400 dark:text-slate-600 text-xs mt-1">Subscriber Portal v3.0</p>
      </footer>

      <!-- PC Footer -->
      <footer class="hidden md:block bg-white dark:bg-[#192233] border-t border-slate-200 dark:border-slate-700 mt-auto">
        <div class="p-6 text-center text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
          <p class="font-semibold text-slate-700 dark:text-slate-300 mb-2">KISTI Certification Authority</p>
          <p>Global Science experimental Data hub Center</p>
          <p>Korea Institute of Science and Technology Information</p>
          <p class="mt-2 text-slate-400 dark:text-slate-500">245 Daehak-ro, Yuseong-gu 34141 Daejeon, Republic of Korea</p>
        </div>
      </footer>

    </div>
    <!-- /앱 컨테이너 -->

  </div>
  <!-- /메인 영역 -->

</div>
<!-- /flex 컨테이너 -->

<!-- iOS Bottom Tab Bar (모바일에서만 표시) -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/90 dark:bg-[#111722]/90 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800">
  <div class="flex justify-around items-center max-w-[480px] mx-auto h-16 pb-safe">
    <a href="<?=$pkiBasePath?>/" class="flex flex-col items-center gap-1 <?=preg_match('#/subscriber/?$#', $currentPage) || preg_match('#/subscriber/index\.php#', $currentPage) ? 'text-primary' : 'text-slate-500 dark:text-slate-400'?>">
      <span class="material-symbols-outlined" <?=preg_match('#/subscriber/?$#', $currentPage) || preg_match('#/subscriber/index\.php#', $currentPage) ? 'style="font-variation-settings: \'FILL\' 1;"' : ''?>>dashboard</span>
      <span class="text-[10px] <?=preg_match('#/subscriber/?$#', $currentPage) || preg_match('#/subscriber/index\.php#', $currentPage) ? 'font-bold' : 'font-medium'?>">Status</span>
    </a>
    <a href="<?=$pkiBasePath?>/csr_v3.php" class="flex flex-col items-center gap-1 <?=strpos($currentPage, 'csr_v3.php') !== false ? 'text-primary' : 'text-slate-500 dark:text-slate-400'?>">
      <span class="material-symbols-outlined" <?=strpos($currentPage, 'csr_v3.php') !== false ? 'style="font-variation-settings: \'FILL\' 1;"' : ''?>>receipt_long</span>
      <span class="text-[10px] <?=strpos($currentPage, 'csr_v3.php') !== false ? 'font-bold' : 'font-medium'?>">CSR</span>
    </a>
    <a href="<?=$pkiBasePath?>/cert_v3.php" class="flex flex-col items-center gap-1 <?=strpos($currentPage, 'cert_v3.php') !== false ? 'text-primary' : 'text-slate-500 dark:text-slate-400'?>">
      <span class="material-symbols-outlined" <?=strpos($currentPage, 'cert_v3.php') !== false ? 'style="font-variation-settings: \'FILL\' 1;"' : ''?>>verified</span>
      <span class="text-[10px] <?=strpos($currentPage, 'cert_v3.php') !== false ? 'font-bold' : 'font-medium'?>">Certs</span>
    </a>
    <a href="<?=$pkiBasePath?>/request_v3.php" class="flex flex-col items-center gap-1 <?=strpos($currentPage, 'request') !== false ? 'text-primary' : 'text-slate-500 dark:text-slate-400'?>">
      <span class="material-symbols-outlined" <?=strpos($currentPage, 'request') !== false ? 'style="font-variation-settings: \'FILL\' 1;"' : ''?>>add_circle</span>
      <span class="text-[10px] <?=strpos($currentPage, 'request') !== false ? 'font-bold' : 'font-medium'?>">Request</span>
    </a>
  </div>
  <!-- iOS Home Indicator spacer -->
  <div class="h-safe"></div>
</nav>

<!-- JavaScript -->
<script src="<?=$assetsPath?>/js/menu.js"></script>

</body>
</html>
