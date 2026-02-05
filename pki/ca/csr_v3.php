<?php
include("common_v3.php");

function OpenSSLGetSubjectString($dn_obj) {
  $dn = "";
  foreach ($dn_obj as $dir => $val) {
    if (is_array($val)) {
      for ($i = 0; $i < count($val); $i++) {
        $e = $val[$i];
        $dn .= "/$dir=$e";
      }
    } else {
      $dn .= "/$dir=$val";
    }
  }
  return $dn;
}

// View CSR detail
if ($mode == 'view') {
  include("head.php");

  $csrid = $form['csrid'];
  $qry = "SELECT * FROM csr WHERE csrid='$csrid'";
  $row = DBQueryAndFetchRow($qry);

  $csr = trim($row['csr']);
  $csr_block = $csr;
  $csr_block = preg_replace('/[-]+BEGIN CERTIFICATE REQUEST[-]+/', "", $csr_block);
  $csr_block = preg_replace('/[-]+END CERTIFICATE REQUEST[-]+/', "", $csr_block);
  if (!preg_match("/BEGIN/", $csr_block)) {
    $csr_block = "-----BEGIN CERTIFICATE REQUEST-----\n$csr_block\n-----END CERTIFICATE REQUEST-----\n";
  }
  $csr_block = preg_replace("/\r/", "", $csr_block);
  $csr_block = preg_replace("/\n\n/", "\n", $csr_block);

  $tmp = '/tmp';
  $file = "$tmp/cert.csr";
  $fp = fopen($file, 'w');
  fputs($fp, $csr_block);
  fclose($fp);

  $cmd = "/usr/bin/openssl req -noout -text -in $file";
  unset($out);
  $ret = exec($cmd, $out, $retval);
  $csr_view = join("\n", $out);

  $cmd = "/usr/bin/openssl req -verify -noout -in $file 2>&1";
  unset($out);
  $ret = exec($cmd, $out, $retval);
  $verify_result = join("\n", $out);

  $forminfo = $row['forminfo'];
  $fis = explode('|', $forminfo);
  $dnc = array();
  for ($i = 0; $i < count($fis); $i++) {
    $fi = $fis[$i];
    list($k, $v) = explode('=', $fi, 2);
    $dnc[$k] = $v;
  }

  if ($row['csrtype'] == 'user') $conf_file = 'sign.user.conf';
  else if ($row['csrtype'] == 'host') $conf_file = 'sign.host.conf.tmp';
  else $conf_file = 'error_unknown_csrtype';

  $sed_cmd = '';
  if ($row['csrtype'] == 'host') {
    $fqdn = $dnc['CN'];
    $sed_cmd = "sed -e \"s/____FQDN____/$fqdn/\" sign.host.conf > sign.host.conf.tmp";
  }

  $qry2 = "SELECT MAX(serial) AS max FROM cert";
  $row2 = DBQueryAndFetchRow($qry2);
  $max = $row2['max'] + 1;
  $serial = dechex($max);

  $status = $row['status'];
?>

<!-- Page Header -->
<div class="flex items-center gap-3 pb-4 mb-6 border-b-2 border-red-500">
  <span class="material-symbols-outlined text-red-500">pending_actions</span>
  <h1 class="text-xl font-bold text-slate-900">CSR Information</h1>
</div>

<!-- CSR Status Card -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-4 py-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
          <span class="material-symbols-outlined text-white">receipt_long</span>
        </div>
        <div>
          <p class="text-white/80 text-xs uppercase tracking-wider">CSR #<?php echo htmlspecialchars($row['csrid']); ?></p>
          <p class="text-white font-bold"><?php echo htmlspecialchars($row['csrtype']); ?> certificate request</p>
        </div>
      </div>
      <?php if ($status == 'revoked'): ?>
        <span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-bold">Revoked</span>
      <?php elseif ($status == 'issued'): ?>
        <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-bold">Issued</span>
      <?php else: ?>
        <span class="px-3 py-1 bg-white/20 text-white rounded-full text-xs font-bold"><?php echo htmlspecialchars($status); ?></span>
      <?php endif; ?>
    </div>
  </div>

  <div class="p-4 space-y-3">
    <?php if ($row['certid'] > 0): ?>
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Certificate</span>
      <a href="cert_v3.php?mode=view&certid=<?php echo $row['certid']; ?>" class="text-red-600 font-medium hover:underline">
        CERT #<?php echo $row['certid']; ?>
      </a>
    </div>
    <?php endif; ?>

    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Upload Time</span>
      <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['idate']); ?></span>
    </div>

    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Type</span>
      <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase"><?php echo htmlspecialchars($row['csrtype']); ?></span>
    </div>

    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Email</span>
      <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-red-600"><?php echo htmlspecialchars($row['email']); ?></a>
    </div>

    <div class="py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Subject Information</span>
      <div class="bg-slate-50 rounded-lg p-3">
        <?php foreach ($dnc as $k => $v): ?>
          <div class="flex justify-between text-sm py-1">
            <span class="text-slate-500"><?php echo htmlspecialchars($k); ?></span>
            <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($v); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="py-3">
      <span class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Verify Result</span>
      <span class="<?php echo strpos($verify_result, 'OK') !== false ? 'text-green-600' : 'text-red-600'; ?> font-mono text-sm">
        <?php echo htmlspecialchars($verify_result); ?>
      </span>
    </div>
  </div>
</div>

<!-- CSR Content -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <button onclick="document.getElementById('csr_content').classList.toggle('hidden')" class="w-full px-4 py-3 text-left flex items-center justify-between hover:bg-slate-50 transition-colors">
    <span class="font-bold text-slate-900 flex items-center gap-2">
      <span class="material-symbols-outlined text-slate-400">code</span>
      CSR (PEM)
    </span>
    <span class="material-symbols-outlined text-slate-400">expand_more</span>
  </button>
  <div id="csr_content" class="hidden border-t border-slate-200 p-4">
    <pre class="text-xs font-mono bg-slate-50 p-4 rounded-lg overflow-x-auto"><?php echo htmlspecialchars($csr); ?></pre>
  </div>
</div>

<!-- CSR Details -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <button onclick="document.getElementById('csr_view').classList.toggle('hidden')" class="w-full px-4 py-3 text-left flex items-center justify-between hover:bg-slate-50 transition-colors">
    <span class="font-bold text-slate-900 flex items-center gap-2">
      <span class="material-symbols-outlined text-slate-400">info</span>
      CSR Details
    </span>
    <span class="material-symbols-outlined text-slate-400">expand_more</span>
  </button>
  <div id="csr_view" class="hidden border-t border-slate-200 p-4">
    <pre class="text-xs font-mono bg-slate-50 p-4 rounded-lg overflow-x-auto max-h-64 overflow-y-auto"><?php echo htmlspecialchars($csr_view); ?></pre>
  </div>
</div>

<!-- Signing Script -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
    <h3 class="font-bold text-slate-900 flex items-center gap-2">
      <span class="material-symbols-outlined text-slate-400">terminal</span>
      Signing Script (Serial: <?php echo $serial; ?>)
    </h3>
  </div>
  <div class="p-4 space-y-4">
    <div>
      <label class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Create & Sign</label>
      <textarea class="w-full h-40 font-mono text-xs bg-slate-900 text-green-400 p-4 rounded-lg" readonly>export dir=<?php echo $serial; ?>

cd /kistica/ca_v3/
mkdir $dir
echo "<?php echo $csr_block; ?>" > $dir/csr.pem

<?php echo $sed_cmd; ?>

openssl ca -config <?php echo $conf_file; ?> -out $dir/cert.pem -infiles $dir/csr.pem</textarea>
    </div>
    <div>
      <label class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Move & Download</label>
      <textarea class="w-full h-16 font-mono text-xs bg-slate-900 text-green-400 p-4 rounded-lg" readonly>mv $dir/cert.pem $dir/<?php echo $serial; ?>.pem
sz $dir/<?php echo $serial; ?>.pem</textarea>
    </div>
  </div>
</div>

<?php if ($row['certid'] <= 0): ?>
<!-- Upload Certificate -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="px-4 py-3 border-b border-slate-100 bg-amber-50">
    <h3 class="font-bold text-slate-900 flex items-center gap-2">
      <span class="material-symbols-outlined text-amber-500">upload_file</span>
      Upload Certificate
    </h3>
  </div>
  <form name="form_cert_upload" method="post" action="<?php echo $env['self']; ?>" enctype="multipart/form-data" class="p-4">
    <div class="mb-4">
      <label class="text-sm font-medium text-slate-700 block mb-2">Certificate Type</label>
      <div class="flex gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="ctype" value="user" class="text-red-500">
          <span class="text-sm">User</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="radio" name="ctype" value="host" checked class="text-red-500">
          <span class="text-sm">Host</span>
        </label>
      </div>
    </div>
    <div class="mb-4">
      <label class="text-sm font-medium text-slate-700 block mb-2">Certificate File (<?php echo $serial; ?>.pem)</label>
      <input type="file" name="file1" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm">
    </div>
    <input type="hidden" name="csrid" value="<?php echo $csrid; ?>">
    <input type="hidden" name="mode" value="upload">
    <button type="button" id="submitBtn" data-id="<?php echo $serial; ?>"
      class="w-full bg-amber-500 hover:bg-amber-600 text-white py-3 rounded-xl font-bold transition-all">
      Upload Certificate
    </button>
  </form>
</div>

<script>
document.getElementById('submitBtn').addEventListener('click', function(e) {
  var serial = e.target.dataset.id;
  var form = document.form_cert_upload;
  var str = form.file1.value;
  if (str.search(serial) == -1) {
    alert("Check the selected file. File name should be " + serial + ".pem");
    return;
  }
  form.submit();
});
</script>
<?php endif; ?>

<!-- Back Link -->
<div class="text-center">
  <a href="csr_v3.php" class="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">arrow_back</span>
    Back to CSR List
  </a>
</div>

<?php
  include("tail.php");
  exit;
}

// Upload certificate
if ($mode == 'upload') {
  $postfile = $_FILES['file1'];
  $tmpname = $postfile['tmp_name'];

  if ($tmpname == '') iError('upload error');
  $name = $postfile['name'];
  $target = "/tmp/$name";
  $ret = copy($tmpname, $target);
  $path = $target;

  $cert_text = file_get_contents($path);
  $csrid = $form['csrid'];

  $openssl_obj_cert = openssl_x509_parse($cert_text);
  if (!$openssl_obj_cert) iError('error');

  $subject = OpenSSLGetSubjectString($openssl_obj_cert['subject']);
  $issuer = OpenSSLGetSubjectString($openssl_obj_cert['issuer']);
  $serial = $openssl_obj_cert['serialNumber'];
  $notbefore_time_t = $openssl_obj_cert['validFrom_time_t'];
  $notbefore_datetime = date("Y-m-d H:i:s", $notbefore_time_t);
  $notafter_time_t = $openssl_obj_cert['validTo_time_t'];
  $notafter_datetime = date("Y-m-d H:i:s", $notafter_time_t);

  list($hex, $ext) = preg_split("/\./", $name);

  $pemfile = "/kistica/html/pub/issued_v3/{$hex}.pem";
  $txtfile = "/kistica/html/pub/issued_v3/{$hex}.txt";
  $crtfile = "/kistica/html/pub/issued_v3/{$hex}.crt";

  $cmd = "/bin/grep \"^[^ ]\" $tmpname | grep -v Certificate > $pemfile";
  exec($cmd);

  $cmd = "chmod 644 $pemfile";
  exec($cmd);

  $cmd = "/usr/bin/openssl x509 -in $pemfile -text > $txtfile";
  exec($cmd);

  $cmd = "/bin/cp $pemfile $crtfile";
  exec($cmd);

  $qry = "SELECT certid FROM counter";
  $row = DBQueryAndFetchRow($qry);
  $certid = $row['certid'] + 1;
  $qry = "UPDATE counter SET certid=certid+1";
  $ret = DBQuery($qry);

  $ctype = $form['ctype'];

  $qry = "INSERT INTO cert SET certid='$certid'"
        .",serial='$serial',subject='$subject'"
        .",vfrom='$notbefore_datetime',vuntil='$notafter_datetime'"
        .",ctype='$ctype'"
        .",status='issued'"
        .",cert='$cert_text'"
        .",csrid='$csrid'"
        .",idate=NOW()";
  $ret = DBQuery($qry);

  $qry = "UPDATE csr SET certid='$certid'"
        .",status='issued'"
        ." WHERE csrid='$csrid'";
  $ret = DBQuery($qry);

  Redirect("csr_v3.php");
  exit;
}

// Delete CSR
if ($mode == 'del') {
  $csrid = $form['csrid'];
  $qry = "DELETE FROM csr WHERE csrid='$csrid'";
  $ret = DBQuery($qry);
  Redirect("csr_v3.php");
  exit;
}

// List view (default)
include("head.php");

$qry = "SELECT count(*) AS count FROM csr";
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

$qry = "SELECT * FROM csr ORDER BY idate DESC LIMIT $start,$ipp";
$ret = DBQuery($qry);
$csrs = array();
while ($row = DBFetchRow($ret)) {
  $csrs[] = $row;
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-red-500">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-red-500">pending_actions</span>
    <h1 class="text-xl font-bold text-slate-900">CSR Management</h1>
  </div>
  <span class="text-sm text-slate-500"><?php echo $total; ?> total</span>
</div>

<!-- Stats -->
<div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
  <div class="flex items-center justify-between text-sm text-slate-500">
    <span>Page <?php echo $page; ?> / <?php echo $last; ?></span>
    <span><?php echo $pager; ?></span>
  </div>
</div>

<?php if (count($csrs) == 0): ?>
<!-- Empty State -->
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
  <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">inbox</span>
  <p class="text-slate-500">No CSRs found</p>
</div>
<?php else: ?>

<!-- Desktop Table -->
<div class="hidden md:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-slate-50 border-b border-slate-200">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">ID</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Subject</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Date</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Type</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Cert</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($csrs as $row):
          $certid = $row['certid'];
        ?>
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3 font-mono text-sm"><?php echo $row['csrid']; ?></td>
          <td class="px-4 py-3">
            <a href="<?php echo $env['self']; ?>?mode=view&csrid=<?php echo $row['csrid']; ?>" class="text-red-600 hover:underline text-sm">
              <?php echo htmlspecialchars($row['forminfo']); ?>
            </a>
          </td>
          <td class="px-4 py-3 text-sm text-slate-500"><?php echo substr($row['idate'], 0, 10); ?></td>
          <td class="px-4 py-3">
            <span class="text-xs px-2 py-1 rounded bg-slate-100"><?php echo $row['csrtype']; ?></span>
          </td>
          <td class="px-4 py-3">
            <?php if ($row['status'] == 'issued'): ?>
              <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700">Issued</span>
            <?php elseif ($row['status'] == 'revoked'): ?>
              <span class="text-xs px-2 py-1 rounded bg-red-100 text-red-700">Revoked</span>
            <?php else: ?>
              <span class="text-xs px-2 py-1 rounded bg-amber-100 text-amber-700"><?php echo $row['status']; ?></span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3 text-sm">
            <?php if ($certid > 0): ?>
              <a href="cert_v3.php?mode=view&certid=<?php echo $certid; ?>" class="text-red-600 hover:underline">#<?php echo $certid; ?></a>
            <?php else: ?>
              <span class="text-slate-400">-</span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3">
            <button class="delete-button text-slate-400 hover:text-red-500 transition-colors" data-id="<?php echo $row['csrid']; ?>">
              <span class="material-symbols-outlined text-sm">delete</span>
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3 mb-6">
  <?php foreach ($csrs as $row):
    $certid = $row['certid'];
  ?>
  <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
    <div class="flex justify-between items-start mb-2">
      <a href="<?php echo $env['self']; ?>?mode=view&csrid=<?php echo $row['csrid']; ?>" class="font-mono text-xs text-red-600">
        CSR #<?php echo $row['csrid']; ?>
      </a>
      <?php if ($row['status'] == 'issued'): ?>
        <span class="text-[10px] px-2 py-0.5 rounded bg-green-100 text-green-700 font-bold">Issued</span>
      <?php elseif ($row['status'] == 'revoked'): ?>
        <span class="text-[10px] px-2 py-0.5 rounded bg-red-100 text-red-700 font-bold">Revoked</span>
      <?php else: ?>
        <span class="text-[10px] px-2 py-0.5 rounded bg-amber-100 text-amber-700 font-bold"><?php echo $row['status']; ?></span>
      <?php endif; ?>
    </div>
    <p class="text-sm text-slate-900 mb-2 break-all"><?php echo htmlspecialchars($row['forminfo']); ?></p>
    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
      <span><?php echo $row['csrtype']; ?> | <?php echo substr($row['idate'], 0, 10); ?></span>
      <div class="flex items-center gap-3">
        <?php if ($certid > 0): ?>
          <a href="cert_v3.php?mode=view&certid=<?php echo $certid; ?>" class="text-red-600">Cert #<?php echo $certid; ?></a>
        <?php endif; ?>
        <button class="delete-button text-slate-400" data-id="<?php echo $row['csrid']; ?>">
          <span class="material-symbols-outlined text-sm">delete</span>
        </button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php endif; ?>

<script>
document.querySelectorAll('.delete-button').forEach(function(button) {
  button.addEventListener('click', function() {
    var csrid = this.getAttribute('data-id');
    if (confirm('Delete CSR #' + csrid + '?')) {
      window.location.href = '<?php echo $env['self']; ?>?mode=del&csrid=' + csrid;
    }
  });
});
</script>

<?php
include("tail.php");
?>
