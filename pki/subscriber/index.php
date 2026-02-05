<?php
include("common_v3.php");
include("head.php");

// WACC 정보 추출
$wacc_subject = isset($_SERVER['SSL_CLIENT_S_DN']) ? $_SERVER['SSL_CLIENT_S_DN'] : 'N/A';
$wacc_valid_from = isset($_SERVER['SSL_CLIENT_V_START']) ? $_SERVER['SSL_CLIENT_V_START'] : 'N/A';
$wacc_valid_until = isset($_SERVER['SSL_CLIENT_V_END']) ? $_SERVER['SSL_CLIENT_V_END'] : 'N/A';
$wacc_issuer = isset($_SERVER['SSL_SERVER_I_DN']) ? $_SERVER['SSL_SERVER_I_DN'] : 'N/A';

// Extract CN from subject
$wacc_cn = 'User';
if (preg_match('/CN\s*=\s*([^,\/]+)/', $wacc_subject, $matches)) {
  $wacc_cn = trim($matches[1]);
}
?>

<!-- Primary Certificate Status Card -->
<div class="flex flex-col items-stretch justify-start rounded-xl shadow-lg bg-white dark:bg-[#192233] overflow-hidden border border-slate-200 dark:border-slate-800 mb-6">
  <div class="w-full h-32 bg-gradient-to-br from-primary via-[#0a48c4] to-[#101622] flex items-center justify-center relative">
    <span class="material-symbols-outlined text-white/20 text-8xl absolute right-[-10px] bottom-[-10px]">verified_user</span>
    <div class="flex flex-col items-center">
      <div class="bg-white/20 backdrop-blur-sm p-3 rounded-full mb-2">
        <span class="material-symbols-outlined text-white text-3xl">check_circle</span>
      </div>
      <span class="text-white font-bold tracking-widest text-xs uppercase">Identity Secured</span>
    </div>
  </div>
  <div class="flex flex-col p-5 gap-1">
    <div class="flex justify-between items-start">
      <div>
        <p class="text-primary dark:text-[#92a4c9] text-xs font-bold leading-normal uppercase tracking-wider">Authenticated as</p>
        <p class="text-xl font-bold leading-tight tracking-tight mt-1 text-slate-900 dark:text-white"><?php echo htmlspecialchars($wacc_cn); ?></p>
      </div>
      <div class="bg-green-500/10 text-green-500 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-tighter">Secure</div>
    </div>
    <div class="mt-4 space-y-2">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-sm text-slate-400">person</span>
        <p class="text-slate-600 dark:text-[#92a4c9] text-sm font-medium truncate"><?php echo htmlspecialchars(substr($wacc_subject, 0, 60)); ?>...</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-sm text-slate-400">calendar_today</span>
        <p class="text-slate-600 dark:text-[#92a4c9] text-sm">Valid: <?php echo htmlspecialchars(substr($wacc_valid_from, 0, 11)); ?> ~ <?php echo htmlspecialchars(substr($wacc_valid_until, 0, 11)); ?></p>
      </div>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 gap-3 mb-6">
  <a href="request_user_cert_v3.php" class="flex flex-col gap-2 items-center justify-center rounded-xl h-24 bg-primary text-white shadow-md active:bg-primary/90 transition-all active:scale-[0.98]">
    <span class="material-symbols-outlined text-2xl">add_moderator</span>
    <span class="text-sm font-bold">Request New</span>
  </a>
  <a href="cert_v3.php" class="flex flex-col gap-2 items-center justify-center rounded-xl h-24 bg-white dark:bg-[#232f48] text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 shadow-sm transition-all active:scale-[0.98]">
    <span class="material-symbols-outlined text-2xl">history_edu</span>
    <span class="text-sm font-bold">My Certificates</span>
  </a>
</div>

<!-- Available Services Section -->
<div class="flex items-center justify-between pb-2 pt-4">
  <h3 class="text-lg font-bold leading-tight tracking-tight text-slate-900 dark:text-white">Available Services</h3>
</div>

<div class="space-y-3">
  <!-- User Certificate Request -->
  <a href="request_user_cert_v3.php" class="flex gap-4 bg-white dark:bg-[#192233] p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all active:scale-[0.99]">
    <div class="flex items-center justify-center rounded-lg bg-green-500 shrink-0 w-12 h-12 text-white">
      <span class="material-symbols-outlined">person_add</span>
    </div>
    <div class="flex flex-1 flex-col justify-center min-w-0">
      <p class="text-slate-900 dark:text-white text-base font-bold truncate">User Certificate</p>
      <p class="text-slate-500 dark:text-[#92a4c9] text-xs font-normal truncate mt-0.5">Request a new personal certificate</p>
    </div>
    <div class="shrink-0 flex items-center">
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>

  <!-- Host Certificate Request -->
  <a href="request_host_cert_v3.php" class="flex gap-4 bg-white dark:bg-[#192233] p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all active:scale-[0.99]">
    <div class="flex items-center justify-center rounded-lg bg-blue-500 shrink-0 w-12 h-12 text-white">
      <span class="material-symbols-outlined">dns</span>
    </div>
    <div class="flex flex-1 flex-col justify-center min-w-0">
      <p class="text-slate-900 dark:text-white text-base font-bold truncate">Host Certificate</p>
      <p class="text-slate-500 dark:text-[#92a4c9] text-xs font-normal truncate mt-0.5">Request a certificate for your server</p>
    </div>
    <div class="shrink-0 flex items-center">
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>

  <!-- My CSRs -->
  <a href="csr_v3.php" class="flex gap-4 bg-white dark:bg-[#192233] p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all active:scale-[0.99]">
    <div class="flex items-center justify-center rounded-lg bg-amber-500 shrink-0 w-12 h-12 text-white">
      <span class="material-symbols-outlined">pending_actions</span>
    </div>
    <div class="flex flex-1 flex-col justify-center min-w-0">
      <p class="text-slate-900 dark:text-white text-base font-bold truncate">My CSRs</p>
      <p class="text-slate-500 dark:text-[#92a4c9] text-xs font-normal truncate mt-0.5">View your certificate signing requests</p>
    </div>
    <div class="shrink-0 flex items-center">
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>

  <!-- My Certificates -->
  <a href="cert_v3.php" class="flex gap-4 bg-white dark:bg-[#192233] p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all active:scale-[0.99]">
    <div class="flex items-center justify-center rounded-lg bg-purple-500 shrink-0 w-12 h-12 text-white">
      <span class="material-symbols-outlined">verified</span>
    </div>
    <div class="flex flex-1 flex-col justify-center min-w-0">
      <p class="text-slate-900 dark:text-white text-base font-bold truncate">My Certificates</p>
      <p class="text-slate-500 dark:text-[#92a4c9] text-xs font-normal truncate mt-0.5">View and manage your issued certificates</p>
    </div>
    <div class="shrink-0 flex items-center">
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>

  <!-- Revocation Request (not use) 
  <a href="revoke_v3.php" class="flex gap-4 bg-white dark:bg-[#192233] p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-all active:scale-[0.99]">
    <div class="flex items-center justify-center rounded-lg bg-red-500 shrink-0 w-12 h-12 text-white">
      <span class="material-symbols-outlined">gpp_maybe</span>
    </div>
    <div class="flex flex-1 flex-col justify-center min-w-0">
      <p class="text-slate-900 dark:text-white text-base font-bold truncate">Revoke Certificate</p>
      <p class="text-slate-500 dark:text-[#92a4c9] text-xs font-normal truncate mt-0.5">Request revocation of a compromised certificate</p>
    </div>
    <div class="shrink-0 flex items-center">
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a> -->
</div>

<!-- WACC Info Card -->
<div class="mt-6 bg-primary/5 border border-primary/20 rounded-xl p-4">
  <div class="flex items-center gap-3 mb-3">
    <span class="material-symbols-outlined text-primary">badge</span>
    <p class="text-sm font-bold text-slate-900 dark:text-white">Web Access Client Certificate</p>
  </div>
  <div class="space-y-2 text-xs">
    <div class="flex justify-between">
      <span class="text-slate-500">Subject</span>
      <span class="text-slate-700 dark:text-slate-300 font-mono truncate max-w-[200px]"><?php echo htmlspecialchars(substr($wacc_subject, 0, 40)); ?>...</span>
    </div>
    <div class="flex justify-between">
      <span class="text-slate-500">Issuer</span>
      <span class="text-slate-700 dark:text-slate-300 font-mono truncate max-w-[200px]"><?php echo htmlspecialchars(substr($wacc_issuer, 0, 40)); ?>...</span>
    </div>
  </div>
</div>

<?php
include("tail.php");
?>
