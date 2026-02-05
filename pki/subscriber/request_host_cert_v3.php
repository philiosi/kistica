<?php

include("common_v3.php");

if ($mode == 'request') {
  $dnc_o = $form['dnc_o'];
  $dnc_cn = $form['dnc_cn'];
  $csr = $form['csr'];

  $forminfo = "CN=$dnc_cn";
  $forminfo = addslashes($forminfo);

  $email = $env['email']; # subscriber's email of WACC

  $qry = "INSERT INTO csr SET csr='$csr'"
        .",forminfo='$forminfo'"
        .",email='$email'"
        .",certid='-1'"
        .",csrtype='host'"
        .",status='upload'"
        .",idate=NOW()";

  $ret = DBQuery($qry);

  $to = $email;
  $subject = "[KISTI CA] CSR (certificate signing request) has been sent to KISTI CA";
  $header = "FROM: 'KISTI CA'<kisti-grid-ca@kisti.re.kr>\n";
  $header .= "CC: 'KISTI CA'<kisti-grid-ca@kisti.re.kr>\n";
  $message =<<<EOS

Your CSR has been requestd to KISTI CA.

KISTI CA will review your CSR, before issuing your certificate.

After KISTI CA issue your certificate, a notification e-mail will be sent to this e-mail address.

Issued certificates can be downloaded from the KISTI CA web site.

If you have any question or problem, please send email to us.

Your CSR is as follows:
--------------------------------------------------------
$csr
--------------------------------------------------------

EOS;
  mail($to, $subject, $message, $header);

  include("head.php");
?>

<!-- Page Title -->
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold text-slate-900 dark:text-white">Request Host Certificate</h1>
</div>

<!-- Success Card -->
<div class="flex flex-col items-stretch justify-start rounded-xl shadow-lg bg-white dark:bg-[#192233] overflow-hidden border border-slate-200 dark:border-slate-800 mb-6">
  <div class="w-full h-24 bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center relative">
    <span class="material-symbols-outlined text-white/20 text-7xl absolute right-[-10px] bottom-[-10px]">check_circle</span>
    <div class="flex flex-col items-center">
      <span class="material-symbols-outlined text-white text-3xl">check_circle</span>
      <span class="text-white font-bold tracking-widest text-xs uppercase mt-1">CSR Submitted</span>
    </div>
  </div>
  <div class="p-5">
    <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed">
      Your Host CSR has been submitted to KISTI CA.<br><br>
      KISTI CA will review your CSR before issuing your certificate.<br><br>
      Your CSR has been sent to your email <strong class="text-slate-900 dark:text-white"><?php echo htmlspecialchars($email); ?></strong>.<br><br>
      After KISTI CA issues your certificate, a notification email will be sent to you.
    </p>
  </div>
</div>

<!-- Action Buttons -->
<div class="flex flex-col gap-3">
  <a href="csr_v3.php" class="w-full h-14 bg-primary text-white font-bold text-base rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
    <span class="material-symbols-outlined">receipt_long</span>
    View My CSRs
  </a>
  <a href="index.php" class="w-full h-14 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-white font-bold text-base rounded-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2">
    <span class="material-symbols-outlined">home</span>
    Back to Home
  </a>
</div>

<?php
  include("tail.php");
  exit;
}

//////////////////////// request host certificate ///////////////////

include("head.php");

$m_serial = $_SERVER['SSL_CLIENT_M_SERIAL'];
$m_serial = hexdec($m_serial);

$qry = "SELECT w.*, s.*"
   ." FROM webcert w"
   ." LEFT JOIN subscriber s ON w.subscid=s.id"
   ." WHERE w.serial='$m_serial'";
$row = DBQueryAndFetchRow($qry);
$org = $row['org'];

?>

<!-- Page Title -->
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold text-slate-900 dark:text-white">Request Host Certificate</h1>
</div>

<!-- Security Status Card -->
<div class="flex flex-1 flex-col items-start justify-between gap-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#192233] p-5 mb-6 shadow-sm">
  <div class="flex flex-col gap-1">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-blue-500 text-xl">dns</span>
      <p class="text-slate-900 dark:text-white text-base font-bold leading-tight">Host Certificate Request</p>
    </div>
    <p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal mt-1">
      Request a certificate for your server or service.
    </p>
  </div>
</div>

<!-- Step 1: Generate CSR -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
  <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
    <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold">1</div>
    <h3 class="font-bold text-slate-900 dark:text-white">Generate CSR</h3>
  </div>
  <div class="p-4">
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
      Generate a CSR for your host using the OpenSSL command on your server.
    </p>

    <div class="flex gap-3 mb-4">
      <a href="host_eng.pdf" target="_blank" class="flex-1 flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
        <span class="material-symbols-outlined text-primary">description</span>
        <span>Guide (English)</span>
      </a>
      <a href="host_kor.pdf" target="_blank" class="flex-1 flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-800 rounded-xl text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
        <span class="material-symbols-outlined text-primary">description</span>
        <span>Guide (Korean)</span>
      </a>
    </div>
  </div>
</div>

<!-- Step 2: Upload CSR -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
  <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
    <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold">2</div>
    <h3 class="font-bold text-slate-900 dark:text-white">Upload CSR</h3>
  </div>
  <div class="p-4">
    <form name='form' method='post' action='<?php echo $env['self']; ?>' class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Country</label>
        <div class="w-full h-12 px-4 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center text-slate-900 dark:text-white">KR</div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Organization</label>
        <div class="w-full h-12 px-4 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center text-slate-900 dark:text-white"><?php echo htmlspecialchars($org); ?></div>
        <input type='hidden' name='dnc_o' value='<?php echo htmlspecialchars($org); ?>'>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Host FQDN</label>
        <input type='text' name='dnc_cn' maxlength='30' placeholder="hostname.example.co.kr" class="w-full h-12 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
        <p class="text-xs text-slate-400 mt-1">e.g., hostname.example.co.kr (max. 30 characters)</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">CSR</label>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Copy and paste your CSR block below:</p>
        <textarea name='csr' rows="10" placeholder="-----BEGIN CERTIFICATE REQUEST-----
...
-----END CERTIFICATE REQUEST-----" class="w-full p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white font-mono text-xs placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary resize-none"></textarea>
      </div>

      <input type='hidden' name='mode' value='request'>
      <input type='hidden' name='rno' value='<?php echo rand(); ?>'>

      <button type='button' id='upcsr' class="w-full h-14 bg-primary text-white font-bold text-base rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
        <span class="material-symbols-outlined">upload</span>
        Upload CSR
      </button>
    </form>
  </div>
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
</div>

<script>
var upcsr_button = document.getElementById('upcsr');
upcsr_button.addEventListener('click', function() {
  upload_csr();
});

function upload_csr() {
  var form = document.forms['form'];
  if (form.dnc_cn.value == '') {
    alert('Please enter the Host FQDN');
    form.dnc_cn.focus();
    return;
  }
  if (form.csr.value == '') {
    alert('Please paste your CSR');
    form.csr.focus();
    return;
  }
  form.submit();
}
</script>

<?php
include("tail.php");
?>
