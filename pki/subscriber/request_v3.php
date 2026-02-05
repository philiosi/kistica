<?php
include('common_v3.php');
include("head.php");
?>

<!-- Page Title -->
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold text-slate-900 dark:text-white">Request Certificate</h1>
</div>

<!-- Page Description -->
<p class="text-slate-500 dark:text-slate-400 text-sm mb-6">
  Select the type of certificate you wish to request. KISTI CA supports all modern browsers.
</p>

<!-- Security Status Card -->
<div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#192233] p-5 mb-6 shadow-sm">
  <div class="flex flex-col gap-1">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-primary text-xl">verified_user</span>
      <p class="text-slate-900 dark:text-white text-base font-bold leading-tight">Secure Connection</p>
    </div>
    <p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal mt-1">
      Your data is encrypted and sent directly to KISTI CA secure servers for validation.
    </p>
  </div>
</div>

<!-- Certificate Type Selection -->
<div class="space-y-3 mb-8">
  <a href="request_user_cert_v3.php" class="group block">
    <div class="flex items-center gap-4 bg-white dark:bg-[#192233] px-5 py-4 justify-between rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-all active:scale-[0.99]">
      <div class="flex items-center gap-4">
        <div class="flex items-center justify-center rounded-xl bg-green-500 text-white shrink-0 w-12 h-12 shadow-sm">
          <span class="material-symbols-outlined text-2xl">person</span>
        </div>
        <div class="flex flex-col justify-center">
          <p class="text-slate-900 dark:text-white text-base font-bold leading-tight">User Certificate Request</p>
          <p class="text-slate-500 dark:text-slate-400 text-sm mt-0.5">Apply for a personal certificate</p>
        </div>
      </div>
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>

  <a href="request_host_cert_v3.php" class="group block">
    <div class="flex items-center gap-4 bg-white dark:bg-[#192233] px-5 py-4 justify-between rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 transition-all active:scale-[0.99]">
      <div class="flex items-center gap-4">
        <div class="flex items-center justify-center rounded-xl bg-blue-500 text-white shrink-0 w-12 h-12 shadow-sm">
          <span class="material-symbols-outlined text-2xl">dns</span>
        </div>
        <div class="flex flex-col justify-center">
          <p class="text-slate-900 dark:text-white text-base font-bold leading-tight">Host Certificate Request</p>
          <p class="text-slate-500 dark:text-slate-400 text-sm mt-0.5">Apply for a server/service certificate</p>
        </div>
      </div>
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>
</div>

<!-- Info Notices -->
<div class="space-y-3">
  <div class="flex gap-3 items-start p-4 rounded-xl bg-blue-50 dark:bg-primary/10 border border-blue-100 dark:border-primary/20">
    <span class="material-symbols-outlined text-primary shrink-0 text-xl">info</span>
    <div class="text-sm text-slate-700 dark:text-slate-300">
      <strong class="block mb-1 font-bold text-slate-900 dark:text-white">Browser Support</strong>
      This function supports all modern browsers (Google Chrome, Edge, Firefox, Safari, etc). IE mode in Edge is no longer necessary.
    </div>
  </div>

  <div class="flex gap-3 items-start p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30">
    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 shrink-0 text-xl">warning</span>
    <div class="text-sm text-slate-700 dark:text-slate-300">
      <strong class="block mb-1 font-bold text-slate-900 dark:text-white">Important: Private Key</strong>
      After uploading your CSR, you <span class="font-bold underline">MUST</span> download your private key file (e.g., <code class="bg-amber-100 dark:bg-amber-900/50 px-1.5 py-0.5 rounded text-xs">csrid_privateKey.pem</code>) from the result page. It cannot be recovered later.
    </div>
  </div>
</div>

<?php
include("tail.php");
?>
