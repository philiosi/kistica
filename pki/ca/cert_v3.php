<?php
include('common_v3.php');

$commonName = $form['dnc_cn'];

// Change certificate type
if ($mode == 'change_type') {
  $certid = $form['certid'];
  $type = $form['type'];

  if ($type == 'user') {
    $qry_set = "ctype='user'";
  } else if ($type == 'host') {
    $qry_set = "ctype='host'";
  } else iError('error');

  $qry = "UPDATE cert SET $qry_set WHERE certid='$certid'";
  $ret = DBQuery($qry);

  Redirect("$env[self]?mode=view&certid=$certid");
  exit;
}

// View certificate detail
if ($mode == 'view') {
  $certid = $form['certid'];

  $qry = "SELECT * FROM cert WHERE certid='$certid'";
  $row = DBQueryAndFetchRow($qry);
  $cert_text = $row['cert'];

  $tmp = '/tmp';
  $file = "$tmp/cert.tmp";
  $fp = fopen($file, 'w');
  fputs($fp, $cert_text);
  fclose($fp);

  $cmd = "/usr/bin/openssl x509 -noout -text -in $file";
  unset($out);
  $ret = exec($cmd, $out, $retval);
  $cert_view = join("\n", $out);

  include("head.php");
?>

<!-- Page Header -->
<div class="flex items-center gap-3 pb-4 mb-6 border-b-2 border-red-500">
  <span class="material-symbols-outlined text-red-500">verified</span>
  <h1 class="text-xl font-bold text-slate-900">Certificate Information</h1>
</div>

<!-- Certificate Card -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-4">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
        <span class="material-symbols-outlined text-white">verified</span>
      </div>
      <div>
        <p class="text-white/80 text-xs uppercase tracking-wider">Certificate #<?php echo htmlspecialchars($row['certid']); ?></p>
        <p class="text-white font-bold"><?php echo htmlspecialchars($row['subject']); ?></p>
      </div>
    </div>
  </div>

  <div class="p-4 space-y-3">
    <!-- Type -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Type</span>
      <div class="flex items-center gap-2">
        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase"><?php echo htmlspecialchars($row['ctype']); ?></span>
        <div class="flex gap-1">
          <a href="<?php echo $env['self']; ?>?mode=change_type&certid=<?php echo $certid; ?>&type=host" class="text-xs text-slate-400 hover:text-slate-600">[host]</a>
          <a href="<?php echo $env['self']; ?>?mode=change_type&certid=<?php echo $certid; ?>&type=user" class="text-xs text-slate-400 hover:text-slate-600">[user]</a>
        </div>
      </div>
    </div>

    <!-- Serial -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Serial</span>
      <span class="text-slate-900 font-mono"><?php echo htmlspecialchars($row['serial']); ?></span>
    </div>

    <!-- Status -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Status</span>
      <?php
        $status = $row['status'];
        if ($status == 'revoked') {
          echo '<span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold uppercase">Revoked</span>';
        } else if ($status == 'expired') {
          echo '<span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-xs font-bold uppercase">Expired</span>';
        } else {
          echo '<span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold uppercase">' . htmlspecialchars($status) . '</span>';
        }
      ?>
    </div>

    <!-- CSR ID -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">CSR ID</span>
      <a href="csr_v3.php?mode=view&csrid=<?php echo $row['csrid']; ?>" class="text-red-600 font-medium hover:underline"><?php echo htmlspecialchars($row['csrid']); ?></a>
    </div>

    <!-- Valid From -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Valid From</span>
      <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['vfrom']); ?></span>
    </div>

    <!-- Valid Until -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Valid Until</span>
      <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['vuntil']); ?></span>
    </div>

    <!-- Issue Date -->
    <div class="flex justify-between items-center py-3">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Issue Date</span>
      <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['idate']); ?></span>
    </div>
  </div>
</div>

<!-- Certificate PEM -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <button onclick="document.getElementById('cert_text').classList.toggle('hidden')" class="w-full px-4 py-3 text-left flex items-center justify-between hover:bg-slate-50 transition-colors">
    <span class="font-bold text-slate-900 flex items-center gap-2">
      <span class="material-symbols-outlined text-slate-400">code</span>
      Certificate (PEM)
    </span>
    <span class="material-symbols-outlined text-slate-400">expand_more</span>
  </button>
  <div id="cert_text" class="hidden border-t border-slate-200 p-4">
    <pre class="text-xs font-mono bg-slate-50 p-4 rounded-lg overflow-x-auto"><?php echo htmlspecialchars($cert_text); ?></pre>
  </div>
</div>

<!-- Certificate Details -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
    <h3 class="font-bold text-slate-900 flex items-center gap-2">
      <span class="material-symbols-outlined text-slate-400">info</span>
      Certificate Details
    </h3>
  </div>
  <div class="p-4">
    <pre class="text-xs font-mono bg-slate-50 p-4 rounded-lg overflow-x-auto max-h-96 overflow-y-auto"><?php echo htmlspecialchars($cert_view); ?></pre>
  </div>
</div>

<!-- Actions -->
<div class="grid grid-cols-2 gap-3 mb-6">
  <button id="delete-button" data-id="<?php echo $certid; ?>"
    class="flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-xl font-bold transition-all">
    <span class="material-symbols-outlined">delete</span>
    Delete
  </button>
  <button id="revoke-button" data-id="<?php echo $certid; ?>"
    class="flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-bold transition-all">
    <span class="material-symbols-outlined">block</span>
    Revoke
  </button>
</div>

<!-- Back Link -->
<div class="text-center">
  <a href="cert_v3.php" class="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">arrow_back</span>
    Back to Certificates
  </a>
</div>

<script>
document.getElementById("delete-button").addEventListener('click', function() {
  var certid = this.getAttribute('data-id');
  if (confirm('Delete certificate #' + certid + '?')) {
    window.location.href = '<?php echo $env['self']; ?>?mode=del&certid=' + certid;
  }
});

document.getElementById("revoke-button").addEventListener('click', function() {
  var certid = this.getAttribute('data-id');
  if (confirm('Revoke certificate #' + certid + '? This action cannot be undone.')) {
    window.location.href = '<?php echo $env['self']; ?>?mode=revoke&certid=' + certid;
  }
});
</script>

<?php
  include("tail.php");
  exit;
}

// Revoke certificate
if ($mode == 'revoke') {
  $certid = $form['certid'];

  $qry = "SELECT * FROM cert WHERE certid='$certid'";
  $row = DBQueryAndFetchRow($qry);
  if (!$row) iError("CERT $certid not found");

  $csrid = $row['csrid'];

  $qry = "UPDATE csr SET status='revoked' WHERE csrid='$csrid'";
  $ret = DBQuery($qry);

  $qry = "UPDATE cert SET status='revoked' WHERE certid='$certid'";
  $ret = DBQuery($qry);

  Redirect("$env[self]?mode=view&certid=$certid");
  exit;
}

// Delete certificate
if ($mode == 'del') {
  $certid = $form['certid'];

  $qry = "SELECT * FROM cert WHERE certid='$certid'";
  $row = DBQueryAndFetchRow($qry);
  if (!$row) iError("CERT $certid not found");

  $csrid = $row['csrid'];

  $qry = "UPDATE csr SET status='delcert',certid=-1 WHERE csrid='$csrid'";
  $ret = DBQuery($qry);

  $qry = "DELETE FROM cert WHERE certid='$certid'";
  $ret = DBQuery($qry);

  Redirect("$env[self]");
  exit;
}

// Export
if ($mode == 'export') {
  header('Content-Type: text/plain');
  $qry = "SELECT * FROM cert ORDER BY idate DESC";
  $ret = DBQuery($qry);

  while ($row = DBFetchRow($ret)) {
    $certid = $row['certid'];
    $csrid = $row['csrid'];
    $subject = $row['subject'];
    $serial = $row['serial'];
    $serial_hex = sprintf("%02x", $row['serial']);
    $notafter = substr($row['vuntil'], 0, 10);
    $status = $row['status'];
    $ctype = $row['ctype'];

    print("$certid\t$serial\t$serial_hex\t$csrid\t$subject\t$ctype\t$status\t$notafter\n");
  }
  exit;
}

// List view (default)
include("head.php");

// Search handling
$sql_where = 'WHERE 1';
$search = '';

if (!empty($_GET['search'])) {
  $search = $_GET['search'];
  $sql_where .= " AND ((subject LIKE '%$search%'))";
} else {
  $sql_where .= " AND status='issued'";
}

$qry = "SELECT count(*) as count FROM cert $sql_where";
$row = DBQueryAndFetchRow($qry);
$total = $row['count'];

$ipp = 20;
$page = isset($form['page']) ? $form['page'] : 1;
if ($page == '') $page = 1;
$last = ceil($total / $ipp);
if ($last == 0) $last = 1;
if ($page > $last) $page = $last;
$start = ($page - 1) * $ipp;

unset($form['page']);
$qs = Qstr($form);
$url = "$env[self]$qs";
$pager = Pager_s($url, $page, $total, $ipp);
?>

<!-- Page Header -->
<div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-red-500">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-red-500">verified</span>
    <h1 class="text-xl font-bold text-slate-900">Issued Certificates</h1>
  </div>
  <a href="<?php echo $env['self']; ?>?mode=export" class="text-xs text-slate-500 hover:text-slate-700">Export</a>
</div>

<!-- Search & Stats -->
<div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
  <form action="<?php echo $env['self']; ?>" method="get" class="flex flex-col md:flex-row gap-4 items-center">
    <div class="flex-1 w-full">
      <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
        placeholder="Search by subject..."
        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-red-500">
    </div>
    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition-all">
      Search
    </button>
  </form>
  <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
    <span>Total: <strong class="text-slate-900"><?php echo $total; ?></strong> certificates</span>
    <span>Page <?php echo $page; ?> / <?php echo $last; ?></span>
  </div>
</div>

<?php
$qry = "SELECT * FROM cert $sql_where ORDER BY idate DESC LIMIT $start,$ipp";
$ret = DBQuery($qry);
$certs = array();
while ($row = DBFetchRow($ret)) {
  $certs[] = $row;
}
?>

<?php if (count($certs) == 0): ?>
<!-- Empty State -->
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
  <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">search_off</span>
  <p class="text-slate-500">No certificates found</p>
</div>
<?php else: ?>

<!-- Desktop Table -->
<div class="hidden md:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-slate-50 border-b border-slate-200">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">ID</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Serial</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Subject</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Type</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Valid Until</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($certs as $row):
          $subject = preg_replace("/\/C=KR\/O=KISTI\/O=GRID\//", ".../", $row['subject']);
          $status = $row['status'];
        ?>
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3 font-mono text-sm"><?php echo $row['certid']; ?></td>
          <td class="px-4 py-3 font-mono text-sm"><?php echo $row['serial']; ?></td>
          <td class="px-4 py-3">
            <a href="<?php echo $env['self']; ?>?mode=view&certid=<?php echo $row['certid']; ?>" class="text-red-600 hover:underline text-sm">
              <?php echo htmlspecialchars($subject); ?>
            </a>
          </td>
          <td class="px-4 py-3">
            <span class="text-xs px-2 py-1 rounded bg-slate-100"><?php echo $row['ctype']; ?></span>
          </td>
          <td class="px-4 py-3">
            <?php if ($status == 'revoked'): ?>
              <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-700">Revoked</span>
            <?php elseif ($status == 'expired'): ?>
              <span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-600">Expired</span>
            <?php else: ?>
              <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700"><?php echo $status; ?></span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3 text-sm text-slate-500"><?php echo substr($row['vuntil'], 0, 10); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3 mb-6">
  <?php foreach ($certs as $row):
    $subject = preg_replace("/\/C=KR\/O=KISTI\/O=GRID\//", ".../", $row['subject']);
    $status = $row['status'];
  ?>
  <a href="<?php echo $env['self']; ?>?mode=view&certid=<?php echo $row['certid']; ?>"
     class="block bg-white rounded-xl border border-slate-200 p-4 shadow-sm active:scale-[0.99] transition-all">
    <div class="flex justify-between items-start mb-2">
      <span class="font-mono text-xs text-slate-400">#<?php echo $row['certid']; ?></span>
      <?php if ($status == 'revoked'): ?>
        <span class="text-[10px] px-2 py-0.5 rounded bg-red-100 text-red-700 font-bold">Revoked</span>
      <?php elseif ($status == 'expired'): ?>
        <span class="text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-bold">Expired</span>
      <?php else: ?>
        <span class="text-[10px] px-2 py-0.5 rounded bg-green-100 text-green-700 font-bold"><?php echo $status; ?></span>
      <?php endif; ?>
    </div>
    <p class="font-medium text-slate-900 text-sm mb-2 break-all"><?php echo htmlspecialchars($subject); ?></p>
    <div class="flex items-center justify-between text-xs text-slate-500">
      <span><?php echo $row['ctype']; ?> | Serial: <?php echo $row['serial']; ?></span>
      <span>Until <?php echo substr($row['vuntil'], 0, 10); ?></span>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<?php endif; ?>

<!-- Pagination -->
<?php if ($total > $ipp): ?>
<div class="text-center text-sm text-slate-500">
  <?php echo $pager; ?>
</div>
<?php endif; ?>

<?php
include("tail.php");
?>
