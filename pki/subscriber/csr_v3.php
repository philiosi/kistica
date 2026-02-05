<?php

include("common_v3.php");

//$mode = 'view';
//////////////////////// main ///////////////////////////
if ($mode == 'view') {
  include("head.php");

  $csrid = $form['csrid'];

  $qry = "SELECT * FROM csr WHERE csrid='$csrid'";

  $connect = new mysqli($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd'], $conf['dbname']);
  if ($connect->connect_error){
        iError("Unable to connect to MariaDB");
  }

  $result=$connect->query($qry);
  if( !$result ) {
        exit( "Data query error:".$connect->error );
  }

  $row = $result->fetch_assoc();
  $result->free();
  $connect->close();

  $csr = trim($row['csr']);
  $csr_block = $csr;
  $csr_block = preg_replace('/-----BEGIN CERTIFICATE REQUEST-----/', "", $csr_block);
  $csr_block = preg_replace('/-----END CERTIFICATE REQUEST-----/', "", $csr_block);
  $csr_block =<<<EOS
-----BEGIN CERTIFICATE REQUEST-----
$csr_block
-----END CERTIFICATE REQUEST-----

EOS;
  $csr_block = preg_replace("/\r/","",$csr_block);
  $csr_block = preg_replace("/\n\n/","\n",$csr_block);

  $tmp = '/tmp';
  $file = "$tmp/cert.csr";
  $fp = fopen($file, 'w');
  fputs($fp, $csr_block);
  fclose($fp);

  # view CSR
  $cmd = "/usr/bin/openssl req -noout -text -in $file";
  unset($out);
  $ret = exec($cmd, $out, $retval);
  $csr_view = join("\n", $out);

  # verify CSR
  $cmd = "/usr/bin/openssl req -verify -noout -in $file 2>&1";
  unset($out);
  $ret = exec($cmd, $out, $retval);
  $verify_result = join("\n", $out);

  $forminfo = $row['forminfo'];
  $fis = explode('|', $forminfo);
  $forminfo_s = '';
  $dnc = array();
  for ($i = 0; $i < count($fis); $i++) {
    $fi = $fis[$i];
    list($k,$v) = explode('=', $fi, 2);
    $dnc[$k] = $v;
    $forminfo_s .= "$k=$v<br>";
  }

  if ($row['csrtype'] == 'user') $conf_file = 'sign.user.conf';
  else if ($row['csrtype'] == 'host') $conf_file = 'sign.host.conf.tmp';
  else $conf_file = 'error_unknown_csrtype';

  if ($row['csrtype'] == 'host') {
    $fqdn = $dnc['CN'];
    $sed_cmd =<<<EOS
sed -e "s/____FQDN____/$fqdn/" sign.host.conf  > sign.host.conf.tmp

EOS;
  }

  $dir = '111';
  $issue_script=<<<EOS
cd /kistica/ca/
mkdir $dir
echo "$csr_block" > $dir/csr.pem

$sed_cmd

openssl ca -config $conf_file -out $dir/cert.pem -infiles $dir/csr.pem

openssl x509 -in $dir/cert.pem -text -noout > $dir/cert.txt
more $dir/cert.txt

EOS;

  // Status badge
  $status = $row['status'];
  $status_map = array(
    'upload' => array('type' => 'pending', 'label' => 'Pending'),
    'issued' => array('type' => 'success', 'label' => 'Issued'),
    'revoked' => array('type' => 'failed', 'label' => 'Revoked'),
    'expired' => array('type' => 'warning', 'label' => 'Expired')
  );
  $status_info = isset($status_map[$status]) ? $status_map[$status] : array('type' => 'pending', 'label' => ucfirst($status));

  // CSR Type icon
  $type_icon = ($row['csrtype'] == 'user') ? 'person' : 'dns';
  $type_color = ($row['csrtype'] == 'user') ? 'bg-green-500' : 'bg-blue-500';

?>

<!-- Back Link -->
<a href="csr_v3.php" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-primary mb-4">
  <span class="material-symbols-outlined text-lg">arrow_back</span>
  Back to CSR List
</a>

<!-- CSR Header Card -->
<div class="flex flex-col items-stretch justify-start rounded-xl shadow-lg bg-white dark:bg-[#192233] overflow-hidden border border-slate-200 dark:border-slate-800 mb-6">
  <div class="w-full h-24 <?php echo $type_color; ?> flex items-center justify-center relative">
    <span class="material-symbols-outlined text-white/20 text-7xl absolute right-[-10px] bottom-[-10px]"><?php echo $type_icon; ?></span>
    <div class="flex flex-col items-center">
      <span class="material-symbols-outlined text-white text-3xl"><?php echo $type_icon; ?></span>
      <span class="text-white font-bold tracking-widest text-xs uppercase mt-1"><?php echo ucfirst($row['csrtype']); ?> CSR</span>
    </div>
  </div>
  <div class="p-5">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">CSR ID</p>
        <p class="text-2xl font-bold text-slate-900 dark:text-white">#<?php echo htmlspecialchars($row['csrid']); ?></p>
      </div>
      <?php echo StatusBadge($status_info['type'], $status_info['label']); ?>
    </div>
    <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">
      Uploaded <?php echo htmlspecialchars($row['idate']); ?>
    </p>
  </div>
</div>

<!-- Basic Information -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-4 overflow-hidden">
  <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
    <h3 class="font-bold text-slate-900 dark:text-white">Basic Information</h3>
  </div>
  <div class="p-4 space-y-3">
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">CSR ID</span>
      <span class="text-sm text-slate-900 dark:text-white font-medium"><?php echo htmlspecialchars($row['csrid']); ?></span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">Type</span>
      <span class="text-sm text-slate-900 dark:text-white"><?php echo ucfirst(htmlspecialchars($row['csrtype'])); ?></span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">Upload Time</span>
      <span class="text-sm text-slate-900 dark:text-white"><?php echo htmlspecialchars($row['idate']); ?></span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">Email</span>
      <span class="text-sm text-slate-900 dark:text-white"><?php echo htmlspecialchars($row['email']); ?></span>
    </div>
    <div class="flex justify-between items-center py-2">
      <span class="text-xs text-slate-500 font-medium uppercase tracking-wide">Status</span>
      <?php echo StatusBadge($status_info['type'], $status_info['label']); ?>
    </div>
  </div>
</div>

<!-- Subject Information -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-4 overflow-hidden">
  <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
    <h3 class="font-bold text-slate-900 dark:text-white">Subject Information</h3>
  </div>
  <div class="p-4 space-y-2">
<?php
  foreach ($dnc as $k => $v) {
    echo "<div class=\"flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-700/50 last:border-0\">";
    echo "<span class=\"text-xs text-slate-500 font-medium\">" . htmlspecialchars($k) . "</span>";
    echo "<span class=\"text-sm text-slate-900 dark:text-white font-mono break-all max-w-[200px] text-right\">" . htmlspecialchars($v) . "</span>";
    echo "</div>";
  }
?>
  </div>
</div>

<!-- Verify Result -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-4 overflow-hidden">
  <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
    <span class="material-symbols-outlined text-green-500">verified</span>
    <h3 class="font-bold text-slate-900 dark:text-white">Verification Result</h3>
  </div>
  <div class="p-4">
    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
      <pre class="text-sm text-slate-700 dark:text-slate-300 font-mono whitespace-pre-wrap"><?php echo htmlspecialchars($verify_result); ?></pre>
    </div>
  </div>
</div>

<!-- CSR Details (Collapsible) -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-4 overflow-hidden">
  <details>
    <summary class="px-4 py-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center justify-between transition-colors">
      <h3 class="font-bold text-slate-900 dark:text-white">CSR Data</h3>
      <span class="material-symbols-outlined text-slate-400">expand_more</span>
    </summary>
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
      <div class="bg-slate-800 dark:bg-slate-900 rounded-lg p-4 overflow-x-auto">
        <pre class="text-sm text-green-400 font-mono whitespace-pre-wrap break-all"><?php echo htmlspecialchars($csr); ?></pre>
      </div>
    </div>
  </details>
</div>

<!-- CSR Decoded View (Collapsible) -->
<div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-4 overflow-hidden">
  <details>
    <summary class="px-4 py-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center justify-between transition-colors">
      <h3 class="font-bold text-slate-900 dark:text-white">CSR Decoded View</h3>
      <span class="material-symbols-outlined text-slate-400">expand_more</span>
    </summary>
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
      <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4 overflow-x-auto">
        <pre class="text-xs text-slate-700 dark:text-slate-300 font-mono whitespace-pre-wrap"><?php echo htmlspecialchars($csr_view); ?></pre>
      </div>
    </div>
  </details>
</div>

<?php

  include("tail.php");
  exit;
}


include("head.php");
?>

<!-- Page Title -->
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold text-slate-900 dark:text-white">My CSRs</h1>
</div>

<!-- Page Description -->
<p class="text-slate-500 dark:text-slate-400 text-sm mb-6">Your Certificate Signing Requests history. Tap any item to view details.</p>

<?php
  $email = $env['email'];

  $qry = "SELECT * ,csr.status as status ,csr.idate as idate,csr.csrid as csrid
 FROM csr LEFT JOIN cert ON csr.certid=cert.certid WHERE csr.email='$email'
 ORDER BY csr.idate DESC";

  $ret = DBQuery($qry);
  $csr_count = 0;
  $csr_list = array();
  while ($row = DBFetchRow($ret)) {
    $csr_list[] = $row;
    $csr_count++;
  }

if ($csr_count == 0): ?>
<!-- Empty State -->
<div class="flex flex-col items-center justify-center py-16 text-center">
  <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
    <span class="material-symbols-outlined text-4xl text-slate-400">pending_actions</span>
  </div>
  <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No CSRs Found</h3>
  <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 max-w-xs">You haven't submitted any certificate signing requests yet.</p>
  <div class="flex flex-col gap-3 w-full max-w-xs">
    <a href="request_user_cert_v3.php" class="w-full py-3 bg-primary text-white font-bold text-sm rounded-xl text-center transition-all active:scale-[0.98]">Request User Certificate</a>
    <a href="request_host_cert_v3.php" class="w-full py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-white font-bold text-sm rounded-xl text-center transition-all active:scale-[0.98]">Request Host Certificate</a>
  </div>
</div>
<?php else: ?>

<?php
  // Calculate statistics
  $stats = array('upload' => 0, 'issued' => 0, 'revoked' => 0, 'expired' => 0);
  foreach ($csr_list as $row) {
    $s = $row['status'];
    if (isset($stats[$s])) $stats[$s]++;
  }
?>

<!-- Stats Summary -->
<div class="grid grid-cols-4 gap-2 mb-6">
  <div class="bg-white dark:bg-[#192233] rounded-xl p-3 text-center border border-slate-200 dark:border-slate-800">
    <p class="text-xl font-bold text-slate-900 dark:text-white"><?php echo $csr_count; ?></p>
    <p class="text-[10px] text-slate-500 uppercase tracking-wide font-medium">Total</p>
  </div>
  <div class="bg-white dark:bg-[#192233] rounded-xl p-3 text-center border border-slate-200 dark:border-slate-800">
    <p class="text-xl font-bold text-blue-600"><?php echo $stats['upload']; ?></p>
    <p class="text-[10px] text-slate-500 uppercase tracking-wide font-medium">Pending</p>
  </div>
  <div class="bg-white dark:bg-[#192233] rounded-xl p-3 text-center border border-slate-200 dark:border-slate-800">
    <p class="text-xl font-bold text-green-600"><?php echo $stats['issued']; ?></p>
    <p class="text-[10px] text-slate-500 uppercase tracking-wide font-medium">Issued</p>
  </div>
  <div class="bg-white dark:bg-[#192233] rounded-xl p-3 text-center border border-slate-200 dark:border-slate-800">
    <p class="text-xl font-bold text-red-600"><?php echo $stats['revoked']; ?></p>
    <p class="text-[10px] text-slate-500 uppercase tracking-wide font-medium">Revoked</p>
  </div>
</div>

<!-- CSR List -->
<div class="space-y-3">
<?php
  foreach ($csr_list as $row) {
    $csrid = $row['csrid'];

    // Parse form info
    $forminfo = $row['forminfo'];
    $fis = explode('|', $forminfo);
    $forminfo_arr = array();
    $cn_value = '';
    for ($i = 0; $i < count($fis); $i++) {
      $fi = $fis[$i];
      list($k, $v) = explode('=', $fi, 2);
      $forminfo_arr[$k] = $v;
      if ($k == 'CN') $cn_value = $v;
    }

    $csr = trim($row['csr']);
    $status = $row['status'];
    $csrtype = $row['csrtype'];
    $subject = isset($row['subject']) ? $row['subject'] : '';
    $vuntil = isset($row['vuntil']) ? $row['vuntil'] : '';

    // Status mapping
    $status_map = array(
      'upload' => array('type' => 'pending', 'label' => 'Pending', 'icon' => 'sync', 'color' => 'bg-primary'),
      'issued' => array('type' => 'success', 'label' => 'Issued', 'icon' => 'verified', 'color' => 'bg-green-500'),
      'revoked' => array('type' => 'failed', 'label' => 'Revoked', 'icon' => 'gpp_maybe', 'color' => 'bg-red-500'),
      'expired' => array('type' => 'warning', 'label' => 'Expired', 'icon' => 'schedule', 'color' => 'bg-amber-500')
    );
    $status_info = isset($status_map[$status]) ? $status_map[$status] : array('type' => 'pending', 'label' => ucfirst($status), 'icon' => 'pending', 'color' => 'bg-slate-400');

    // Icon based on type
    $type_icon = ($csrtype == 'user') ? 'person' : 'dns';

    // Format date
    $date_short = substr($row['idate'], 0, 10);
    $time_short = substr($row['idate'], 11, 5);
?>
  <a href="<?php echo $env['self']; ?>?mode=view&csrid=<?php echo $csrid; ?>" class="flex items-center gap-4 bg-white dark:bg-[#192233] px-4 min-h-[80px] py-3 justify-between rounded-xl shadow-sm border border-slate-200 dark:border-slate-700/50 transition-all active:scale-[0.99]">
    <div class="flex items-center gap-4">
      <div class="text-white flex items-center justify-center rounded-xl <?php echo $status_info['color']; ?> shrink-0 size-12 shadow-sm">
        <span class="material-symbols-outlined"><?php echo $status_info['icon']; ?></span>
      </div>
      <div class="flex flex-col justify-center">
        <p class="text-slate-900 dark:text-white text-base font-semibold leading-normal line-clamp-1"><?php echo htmlspecialchars($cn_value ? $cn_value : "CSR #$csrid"); ?></p>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal line-clamp-1">ID: #<?php echo $csrid; ?> • <?php echo ucfirst($csrtype); ?></p>
        <p class="text-slate-400 dark:text-slate-500 text-[12px] mt-0.5"><?php echo $date_short; ?> <?php echo $time_short; ?></p>
      </div>
    </div>
    <div class="shrink-0 flex items-center gap-2">
      <?php echo StatusBadge($status_info['type'], $status_info['label']); ?>
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>
<?php
  }
?>
</div>

<?php endif; ?>

<?php
  include("tail.php");
  exit;
?>
