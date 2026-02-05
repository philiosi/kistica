<?php

include("common_v3.php");

#############################################################
### functions {{{

function _date_diff($dt1,$dt2){
  $y1 = substr($dt1,0,4);
  $m1 = substr($dt1,5,2);
  $d1 = substr($dt1,8,2);
  $h1 = substr($dt1,11,2);
  $i1 = substr($dt1,14,2);
  $s1 = substr($dt1,17,2);

  $y2 = substr($dt2,0,4);
  $m2 = substr($dt2,5,2);
  $d2 = substr($dt2,8,2);
  $h2 = substr($dt2,11,2);
  $i2 = substr($dt2,14,2);
  $s2 = substr($dt2,17,2);

  $r1=date('U',mktime($h1,$i1,$s1,$m1,$d1,$y1));
  $r2=date('U',mktime($h2,$i2,$s2,$m2,$d2,$y2));
  $diff = $r1-$r2;
  $days = floor($diff / 3600 / 24);
  return $days;
}

### functions }}}
#############################################################


include("head.php");

$email = $env['email'];

$qry = "SELECT *,csr.status as status, csr.idate as idate, csr.csrid as csrid"
 ." FROM cert"
 ." LEFT JOIN csr ON csr.certid=cert.certid"
 ." WHERE csr.email='$email'"
 ." ORDER BY csr.idate DESC";

$ret = DBQuery($qry);
$db_error = DBError();

// Collect all certificates
$cert_list = array();
while ($row = DBFetchRow($ret)) {
  $cert_list[] = $row;
}
$cert_count = count($cert_list);

?>

<!-- Page Title -->
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl font-bold text-slate-900 dark:text-white">My Certificates</h1>
</div>

<!-- Page Description -->
<p class="text-slate-500 dark:text-slate-400 text-sm mb-6">Your issued certificates. Certificates expiring soon are highlighted for renewal.</p>

<?php if ($db_error): ?>
<div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 mb-6">
  <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
    <span class="material-symbols-outlined">error</span>
    <span class="text-sm font-medium"><?php echo htmlspecialchars($db_error); ?></span>
  </div>
</div>
<?php endif; ?>

<?php if ($cert_count == 0): ?>
<!-- Empty State -->
<div class="flex flex-col items-center justify-center py-16 text-center">
  <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
    <span class="material-symbols-outlined text-4xl text-slate-400">verified</span>
  </div>
  <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No Certificates Found</h3>
  <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 max-w-xs">You don't have any issued certificates yet.</p>
  <div class="flex flex-col gap-3 w-full max-w-xs">
    <a href="request_user_cert_v3.php" class="w-full py-3 bg-primary text-white font-bold text-sm rounded-xl text-center transition-all active:scale-[0.98]">Request User Certificate</a>
    <a href="request_host_cert_v3.php" class="w-full py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-white font-bold text-sm rounded-xl text-center transition-all active:scale-[0.98]">Request Host Certificate</a>
  </div>
</div>
<?php else: ?>

<?php
  // Calculate statistics
  $stats = array('valid' => 0, 'expiring' => 0, 'revoked' => 0, 'expired' => 0);
  $now = date("Y-m-d");
  foreach ($cert_list as $row) {
    $status = $row['status'];
    $vuntil = substr($row['vuntil'], 0, 10);
    if ($status == 'revoked') {
      $stats['revoked']++;
    } else if ($status == 'expired' || $now > $vuntil) {
      $stats['expired']++;
    } else {
      $remains = _date_diff($vuntil, $now);
      if ($remains < 30) {
        $stats['expiring']++;
      } else {
        $stats['valid']++;
      }
    }
  }
?>

<!-- Stats Summary -->
<div class="grid grid-cols-4 gap-2 mb-6">
  <div class="bg-white dark:bg-[#192233] rounded-xl p-3 text-center border border-slate-200 dark:border-slate-800">
    <p class="text-xl font-bold text-slate-900 dark:text-white"><?php echo $cert_count; ?></p>
    <p class="text-[10px] text-slate-500 uppercase tracking-wide font-medium">Total</p>
  </div>
  <div class="bg-white dark:bg-[#192233] rounded-xl p-3 text-center border border-slate-200 dark:border-slate-800">
    <p class="text-xl font-bold text-green-600"><?php echo $stats['valid']; ?></p>
    <p class="text-[10px] text-slate-500 uppercase tracking-wide font-medium">Valid</p>
  </div>
  <div class="bg-white dark:bg-[#192233] rounded-xl p-3 text-center border border-slate-200 dark:border-slate-800">
    <p class="text-xl font-bold text-amber-600"><?php echo $stats['expiring']; ?></p>
    <p class="text-[10px] text-slate-500 uppercase tracking-wide font-medium">Expiring</p>
  </div>
  <div class="bg-white dark:bg-[#192233] rounded-xl p-3 text-center border border-slate-200 dark:border-slate-800">
    <p class="text-xl font-bold text-red-600"><?php echo $stats['revoked']; ?></p>
    <p class="text-[10px] text-slate-500 uppercase tracking-wide font-medium">Revoked</p>
  </div>
</div>

<!-- Certificate List -->
<div class="space-y-3">
<?php
  foreach ($cert_list as $row) {
    $certid = $row['certid'];
    $cert = trim($row['cert']);
    $status = $row['status'];
    $ctype = $row['ctype'];
    $subject = $row['subject'];
    $serial = $row['serial'];
    $vfrom = substr($row['vfrom'], 0, 10);
    $vuntil = substr($row['vuntil'], 0, 10);

    // Calculate D-day
    $remains = 0;
    $is_expiring = false;
    $is_expired = false;
    if ($now <= $vuntil) {
      $remains = _date_diff($vuntil, $now);
      if ($remains < 30) {
        $is_expiring = true;
      }
    } else {
      $is_expired = true;
    }

    // Determine status display
    if ($status == 'revoked') {
      $status_type = 'failed';
      $status_label = 'Revoked';
      $icon_color = 'bg-red-500';
      $icon_name = 'gpp_maybe';
    } else if ($is_expired || $status == 'expired') {
      $status_type = 'warning';
      $status_label = 'Expired';
      $icon_color = 'bg-gray-400';
      $icon_name = 'schedule';
    } else if ($is_expiring) {
      $status_type = 'warning';
      $status_label = "D-$remains";
      $icon_color = 'bg-amber-500';
      $icon_name = 'warning';
    } else {
      $status_type = 'success';
      $status_label = 'Valid';
      $icon_color = 'bg-green-500';
      $icon_name = 'verified';
    }

    // Type icon
    $type_icon = ($ctype == 'user') ? 'person' : 'dns';

    // Extract CN from subject
    $cn = '';
    if (preg_match('/CN\s*=\s*([^,\/]+)/', $subject, $matches)) {
      $cn = trim($matches[1]);
    }
?>
  <div class="bg-white dark:bg-[#192233] rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
    <div class="flex items-center gap-4 p-4" onclick="toggleCert('<?php echo $certid; ?>')" style="cursor: pointer;">
      <div class="w-12 h-12 <?php echo $icon_color; ?> rounded-xl flex items-center justify-center text-white flex-shrink-0 shadow-sm">
        <span class="material-symbols-outlined"><?php echo $icon_name; ?></span>
      </div>
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
          <p class="font-bold text-slate-900 dark:text-white truncate"><?php echo htmlspecialchars($cn ? $cn : "Certificate #$serial"); ?></p>
          <span class="text-[10px] px-1.5 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded font-medium"><?php echo ucfirst($ctype); ?></span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 truncate font-mono mt-0.5"><?php echo htmlspecialchars(substr($subject, 0, 40)); ?>...</p>
        <div class="flex items-center gap-3 mt-1.5 text-[11px] text-slate-400">
          <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[12px]">tag</span>
            <?php echo htmlspecialchars(substr($serial, 0, 8)); ?>...
          </span>
          <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[12px]">calendar_today</span>
            <?php echo $vfrom; ?> ~ <?php echo $vuntil; ?>
          </span>
        </div>
      </div>
      <div class="flex items-center gap-2 flex-shrink-0">
        <?php echo StatusBadge($status_type, $status_label); ?>
        <span class="material-symbols-outlined text-slate-300" id="icon_<?php echo $certid; ?>">expand_more</span>
      </div>
    </div>

    <?php if ($is_expiring && $ctype == 'user'): ?>
    <!-- Renewal Banner -->
    <div class="bg-amber-50 dark:bg-amber-900/20 border-t border-amber-100 dark:border-amber-800/30 px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-2 text-amber-700 dark:text-amber-400">
        <span class="material-symbols-outlined text-lg">warning</span>
        <span class="text-sm font-medium">Expires in <?php echo $remains; ?> days</span>
      </div>
      <a href="request_user_cert_v3.php" class="bg-primary text-white text-xs font-bold py-1.5 px-3 rounded-lg transition-all active:scale-[0.98]">Renew</a>
    </div>
    <?php endif; ?>

    <!-- Expandable Details -->
    <div id="details_<?php echo $certid; ?>" class="hidden border-t border-slate-100 dark:border-slate-700">
      <div class="p-4 bg-slate-50 dark:bg-slate-800/50 space-y-4">
        <div class="grid grid-cols-2 gap-3">
          <div>
            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wide mb-1">Serial Number</p>
            <p class="text-sm text-slate-900 dark:text-white font-mono break-all"><?php echo htmlspecialchars($serial); ?></p>
          </div>
          <div>
            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wide mb-1">Type</p>
            <p class="text-sm text-slate-900 dark:text-white"><?php echo ucfirst($ctype); ?> Certificate</p>
          </div>
          <div>
            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wide mb-1">Valid From</p>
            <p class="text-sm text-slate-900 dark:text-white"><?php echo htmlspecialchars($vfrom); ?></p>
          </div>
          <div>
            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wide mb-1">Valid Until</p>
            <p class="text-sm text-slate-900 dark:text-white">
              <?php echo htmlspecialchars($vuntil); ?>
              <?php if (!$is_expired && $status != 'revoked'): ?>
              <span class="<?php echo $is_expiring ? 'text-amber-600' : 'text-slate-400'; ?> text-xs">(D-<?php echo $remains; ?>)</span>
              <?php endif; ?>
            </p>
          </div>
        </div>
        <div>
          <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wide mb-1">Subject</p>
          <p class="text-sm text-slate-900 dark:text-white font-mono break-all"><?php echo htmlspecialchars($subject); ?></p>
        </div>
        <details class="mt-2">
          <summary class="text-sm text-primary font-medium cursor-pointer hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">code</span>
            View Certificate PEM
          </summary>
          <div class="mt-3 bg-slate-800 dark:bg-slate-900 rounded-lg p-4 overflow-x-auto">
            <pre class="text-xs text-green-400 font-mono whitespace-pre-wrap break-all"><?php echo htmlspecialchars($cert); ?></pre>
          </div>
        </details>
      </div>
    </div>
  </div>
<?php
  }
?>
</div>

<script>
function toggleCert(certid) {
  var details = document.getElementById('details_' + certid);
  var icon = document.getElementById('icon_' + certid);
  if (details.classList.contains('hidden')) {
    details.classList.remove('hidden');
    icon.textContent = 'expand_less';
  } else {
    details.classList.add('hidden');
    icon.textContent = 'expand_more';
  }
}
</script>

<?php endif; ?>

<?php
  include("tail.php");
  exit;
?>
