<?php
include("common_v3.php");
include("head.php");

// WACC 정보 추출
$wacc_subject = isset($_SERVER['SSL_CLIENT_S_DN']) ? $_SERVER['SSL_CLIENT_S_DN'] : 'N/A';
$wacc_valid_from = isset($_SERVER['SSL_CLIENT_V_START']) ? $_SERVER['SSL_CLIENT_V_START'] : 'N/A';
$wacc_valid_until = isset($_SERVER['SSL_CLIENT_V_END']) ? $_SERVER['SSL_CLIENT_V_END'] : 'N/A';
$wacc_serial = isset($_SERVER['SSL_CLIENT_M_SERIAL']) ? $_SERVER['SSL_CLIENT_M_SERIAL'] : 'N/A';
$wacc_issuer = isset($_SERVER['SSL_SERVER_I_DN']) ? $_SERVER['SSL_SERVER_I_DN'] : 'N/A';

// Extract CN from subject
$wacc_cn = 'RA Manager';
if (preg_match('/CN\s*=\s*([^,\/]+)/', $wacc_subject, $matches)) {
  $wacc_cn = trim($matches[1]);
}
?>

<!-- RA Manager Status Card -->
<div class="flex flex-col items-stretch justify-start rounded-xl shadow-lg bg-white overflow-hidden border border-slate-200 mb-6">
  <div class="w-full h-32 bg-gradient-to-br from-amber-500 via-amber-600 to-amber-700 flex items-center justify-center relative">
    <span class="material-symbols-outlined text-white/20 text-8xl absolute right-[-10px] bottom-[-10px]">assignment_ind</span>
    <div class="flex flex-col items-center">
      <div class="bg-white/20 backdrop-blur-sm p-3 rounded-full mb-2">
        <span class="material-symbols-outlined text-white text-3xl">admin_panel_settings</span>
      </div>
      <span class="text-white font-bold tracking-widest text-xs uppercase">RA Manager</span>
    </div>
  </div>
  <div class="flex flex-col p-5 gap-1">
    <div class="flex justify-between items-start">
      <div>
        <p class="text-amber-600 text-xs font-bold leading-normal uppercase tracking-wider">Authenticated as</p>
        <p class="text-xl font-bold leading-tight tracking-tight mt-1 text-slate-900"><?php echo htmlspecialchars($wacc_cn); ?></p>
      </div>
      <div class="bg-green-500/10 text-green-500 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-tighter">Authorized</div>
    </div>
    <div class="mt-4 space-y-2">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-sm text-slate-400">person</span>
        <p class="text-slate-600 text-sm font-medium truncate"><?php echo htmlspecialchars(substr($wacc_subject, 0, 60)); ?>...</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-sm text-slate-400">calendar_today</span>
        <p class="text-slate-600 text-sm">Valid: <?php echo htmlspecialchars(substr($wacc_valid_from, 0, 11)); ?> ~ <?php echo htmlspecialchars(substr($wacc_valid_until, 0, 11)); ?></p>
      </div>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 gap-3 mb-6">
  <a href="newsubscriber_v3.php" class="flex flex-col gap-2 items-center justify-center rounded-xl h-24 bg-amber-500 text-white shadow-md active:bg-amber-600 transition-all active:scale-[0.98]">
    <span class="material-symbols-outlined text-2xl">person_add</span>
    <span class="text-sm font-bold">New Subscriber</span>
  </a>
  <a href="subscribers_v3.php" class="flex flex-col gap-2 items-center justify-center rounded-xl h-24 bg-white text-slate-800 border border-slate-200 shadow-sm transition-all active:scale-[0.98]">
    <span class="material-symbols-outlined text-2xl">group</span>
    <span class="text-sm font-bold">View Subscribers</span>
  </a>
</div>

<!-- WACC Info Card -->
<div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
  <div class="flex items-center gap-3 mb-4">
    <span class="material-symbols-outlined text-amber-600">badge</span>
    <p class="text-sm font-bold text-slate-900">Web Access Client Certificate</p>
  </div>
  <div class="space-y-3 text-sm">
    <div class="flex flex-col md:flex-row md:justify-between py-2 border-b border-amber-200/50">
      <span class="text-slate-500 text-xs uppercase tracking-wider mb-1 md:mb-0">Subject</span>
      <span class="text-slate-700 font-mono text-xs break-all"><?php echo htmlspecialchars($wacc_subject); ?></span>
    </div>
    <div class="flex flex-col md:flex-row md:justify-between py-2 border-b border-amber-200/50">
      <span class="text-slate-500 text-xs uppercase tracking-wider mb-1 md:mb-0">Valid From</span>
      <span class="text-slate-700 font-medium"><?php echo htmlspecialchars($wacc_valid_from); ?></span>
    </div>
    <div class="flex flex-col md:flex-row md:justify-between py-2 border-b border-amber-200/50">
      <span class="text-slate-500 text-xs uppercase tracking-wider mb-1 md:mb-0">Valid Until</span>
      <span class="text-slate-700 font-medium"><?php echo htmlspecialchars($wacc_valid_until); ?></span>
    </div>
    <div class="flex flex-col md:flex-row md:justify-between py-2 border-b border-amber-200/50">
      <span class="text-slate-500 text-xs uppercase tracking-wider mb-1 md:mb-0">Serial Number</span>
      <span class="text-slate-700 font-mono"><?php echo htmlspecialchars($wacc_serial); ?></span>
    </div>
    <div class="flex flex-col md:flex-row md:justify-between py-2">
      <span class="text-slate-500 text-xs uppercase tracking-wider mb-1 md:mb-0">Issuer</span>
      <span class="text-slate-700 font-mono text-xs break-all"><?php echo htmlspecialchars($wacc_issuer); ?></span>
    </div>
  </div>
</div>

<!-- RA Services -->
<div class="flex items-center justify-between pb-2 pt-4">
  <h3 class="text-lg font-bold leading-tight tracking-tight text-slate-900">RA Services</h3>
</div>

<div class="space-y-3">
  <!-- New Subscriber Registration -->
  <a href="newsubscriber_v3.php" class="flex gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm transition-all active:scale-[0.99]">
    <div class="flex items-center justify-center rounded-lg bg-green-500 shrink-0 w-12 h-12 text-white">
      <span class="material-symbols-outlined">person_add</span>
    </div>
    <div class="flex flex-1 flex-col justify-center min-w-0">
      <p class="text-slate-900 text-base font-bold truncate">New Subscriber</p>
      <p class="text-slate-500 text-xs font-normal truncate mt-0.5">Register a new subscriber to the system</p>
    </div>
    <div class="shrink-0 flex items-center">
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>

  <!-- Manage Subscribers -->
  <a href="subscribers_v3.php" class="flex gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm transition-all active:scale-[0.99]">
    <div class="flex items-center justify-center rounded-lg bg-blue-500 shrink-0 w-12 h-12 text-white">
      <span class="material-symbols-outlined">group</span>
    </div>
    <div class="flex flex-1 flex-col justify-center min-w-0">
      <p class="text-slate-900 text-base font-bold truncate">Subscribers</p>
      <p class="text-slate-500 text-xs font-normal truncate mt-0.5">View and manage registered subscribers</p>
    </div>
    <div class="shrink-0 flex items-center">
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>

  <!-- KISTI CA Home -->
  <a href="http://ca.gridcenter.or.kr/" target="_blank" class="flex gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm transition-all active:scale-[0.99]">
    <div class="flex items-center justify-center rounded-lg bg-purple-500 shrink-0 w-12 h-12 text-white">
      <span class="material-symbols-outlined">open_in_new</span>
    </div>
    <div class="flex flex-1 flex-col justify-center min-w-0">
      <p class="text-slate-900 text-base font-bold truncate">KISTI CA Home</p>
      <p class="text-slate-500 text-xs font-normal truncate mt-0.5">Visit the main KISTI CA website</p>
    </div>
    <div class="shrink-0 flex items-center">
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>
</div>

<?php
include("tail.php");
?>
