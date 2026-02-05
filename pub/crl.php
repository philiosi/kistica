<?php
/**
 * KISTI CA - CRL Information Page
 * Certificate Revocation List 조회 및 다운로드
 */
include("head.php");

// CRL 디렉토리 경로 (pub 기준)
$crlDir = $_SERVER['DOCUMENT_ROOT'] . '/CRL';
if (!is_dir($crlDir)) {
  // pub 하위 CRL 디렉토리 시도
  $crlDir = $_SERVER['DOCUMENT_ROOT'] . '/pub/CRL';
}
if (!is_dir($crlDir)) {
  // 개발환경 경로 시도
  $crlDir = dirname(__FILE__) . '/CRL';
}

// CRL 다운로드 URL (직접 접근)
$crlDownloadUrl = '/CRL/';

// CRL 정책 상수
$CRL_VALIDITY_DAYS = 28;  // CRL 유효기간 (일)
$CRL_RENEW_BEFORE = 7;    // 만료 전 갱신 기준 (일)

/**
 * CRL 파일에서 정보 추출
 */
function parseCrlInfo($crlPath) {
  if (!file_exists($crlPath)) {
    return null;
  }

  $output = shell_exec("openssl crl -in " . escapeshellarg($crlPath) . " -text -noout 2>/dev/null");
  if (!$output) {
    return null;
  }

  $info = array(
    'lastUpdate' => null,
    'nextUpdate' => null,
    'crlNumber' => null,
    'issuer' => null,
    'revokedCount' => 0
  );

  // Last Update
  if (preg_match('/Last Update:\s*(.+)$/m', $output, $m)) {
    $info['lastUpdate'] = strtotime($m[1]);
  }

  // Next Update
  if (preg_match('/Next Update:\s*(.+)$/m', $output, $m)) {
    $info['nextUpdate'] = strtotime($m[1]);
  }

  // CRL Number
  if (preg_match('/CRL Number:\s*\n\s*(\d+)/m', $output, $m)) {
    $info['crlNumber'] = $m[1];
  }

  // Issuer
  if (preg_match('/Issuer:\s*(.+)$/m', $output, $m)) {
    $info['issuer'] = trim($m[1]);
  }

  // Revoked certificate count
  $info['revokedCount'] = substr_count($output, 'Serial Number:');

  return $info;
}

/**
 * CRL 상태 판단
 */
function getCrlStatus($nextUpdate, $renewBefore) {
  $now = time();
  $daysLeft = ($nextUpdate - $now) / 86400;

  if ($daysLeft < 0) {
    return array('status' => 'expired', 'label' => 'Expired', 'class' => 'bg-red-100 text-red-700 border-red-200', 'iconClass' => 'text-red-500');
  } elseif ($daysLeft <= $renewBefore) {
    return array('status' => 'expiring', 'label' => 'Renewal Required', 'class' => 'bg-amber-100 text-amber-700 border-amber-200', 'iconClass' => 'text-amber-500');
  } else {
    return array('status' => 'valid', 'label' => 'Valid', 'class' => 'bg-green-100 text-green-700 border-green-200', 'iconClass' => 'text-green-500');
  }
}

// 최신 CRL 파일 찾기
$latestCrlPath = $crlDir . '/kisti-ca-v3.crl';
$latestCrlInfo = parseCrlInfo($latestCrlPath);

// CRL 파일 목록 수집
$crlFiles = array();
if (is_dir($crlDir)) {
  $files = scandir($crlDir);
  foreach ($files as $file) {
    if (preg_match('/^kisti-ca-v3\.([0-9A-Fa-f]+)\.crl$/', $file, $m)) {
      $serial = hexdec($m[1]);
      $filepath = $crlDir . '/' . $file;
      // CRL 파일 파싱하여 발급일 추출
      $crlInfo = parseCrlInfo($filepath);
      $issueDate = $crlInfo ? $crlInfo['lastUpdate'] : filemtime($filepath);
      $crlFiles[] = array(
        'filename' => $file,
        'serial' => $serial,
        'serialHex' => strtoupper($m[1]),
        'filepath' => $filepath,
        'size' => filesize($filepath),
        'mtime' => filemtime($filepath),
        'issueDate' => $issueDate
      );
    }
  }
}

// Serial 번호 내림차순 정렬
usort($crlFiles, function($a, $b) {
  return $b['serial'] - $a['serial'];
});

// 페이징
$total = count($crlFiles);
$ipp = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$last = ceil($total / $ipp);
if ($last == 0) $last = 1;
if ($page > $last) $page = $last;
$start = ($page - 1) * $ipp;

$pagedFiles = array_slice($crlFiles, $start, $ipp);

// 상태 계산
$crlStatus = null;
$daysUntilExpiry = 0;
$daysUntilRenewal = 0;
if ($latestCrlInfo && $latestCrlInfo['nextUpdate']) {
  $crlStatus = getCrlStatus($latestCrlInfo['nextUpdate'], $CRL_RENEW_BEFORE);
  $daysUntilExpiry = floor(($latestCrlInfo['nextUpdate'] - time()) / 86400);
  $daysUntilRenewal = $daysUntilExpiry - ($CRL_VALIDITY_DAYS - $CRL_RENEW_BEFORE);
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-primary">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-primary">security</span>
    <h1 class="text-xl font-bold text-slate-900">Certificate Revocation List</h1>
  </div>
  <a href="<?php echo $crlDownloadUrl; ?>kisti-ca-v3.crl" class="text-xs text-slate-500 hover:text-slate-700 flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">download</span>
    Download Latest
  </a>
</div>

<?php if ($latestCrlInfo): ?>
<!-- Current CRL Status Card -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="bg-gradient-to-r from-primary to-blue-600 px-4 py-6">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-3xl">security</span>
        </div>
        <div>
          <h2 class="text-white text-xl font-bold">Current CRL</h2>
          <p class="text-white/80 text-sm mt-1">CRL Number: <?php echo htmlspecialchars($latestCrlInfo['crlNumber']); ?></p>
        </div>
      </div>
      <?php if ($crlStatus): ?>
      <div class="<?php echo $crlStatus['class']; ?> px-4 py-2 rounded-lg border">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined <?php echo $crlStatus['iconClass']; ?>">
            <?php echo $crlStatus['status'] == 'valid' ? 'check_circle' : ($crlStatus['status'] == 'expiring' ? 'warning' : 'error'); ?>
          </span>
          <span class="font-bold"><?php echo $crlStatus['label']; ?></span>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="p-4 space-y-3">
    <!-- Issuer -->
    <div class="flex flex-col md:flex-row md:justify-between py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider mb-1 md:mb-0">Issuer</span>
      <span class="text-slate-900 font-mono text-sm"><?php echo htmlspecialchars($latestCrlInfo['issuer']); ?></span>
    </div>

    <!-- Last Update -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Last Update (Issue Date)</span>
      <span class="text-slate-900 font-medium"><?php echo date('Y-m-d H:i:s', $latestCrlInfo['lastUpdate']); ?> UTC</span>
    </div>

    <!-- Next Update -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Next Update (Expiry Date)</span>
      <div class="text-right">
        <span class="text-slate-900 font-medium"><?php echo date('Y-m-d H:i:s', $latestCrlInfo['nextUpdate']); ?> UTC</span>
        <?php if ($daysUntilExpiry >= 0): ?>
        <span class="text-xs text-slate-500 ml-2">(<?php echo $daysUntilExpiry; ?> days left)</span>
        <?php else: ?>
        <span class="text-xs text-red-500 ml-2">(Expired <?php echo abs($daysUntilExpiry); ?> days ago)</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Revoked Certificates Count -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Revoked Certificates</span>
      <span class="text-slate-900 font-bold text-lg"><?php echo number_format($latestCrlInfo['revokedCount']); ?></span>
    </div>

    <!-- Validity Period -->
    <div class="flex justify-between items-center py-3">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Validity Period</span>
      <span class="text-slate-900 font-medium"><?php echo $CRL_VALIDITY_DAYS; ?> days (Renewal: <?php echo $CRL_RENEW_BEFORE; ?> days before expiry)</span>
    </div>
  </div>
</div>

<!-- Timeline Info -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
  <!-- Issue Date -->
  <div class="bg-white rounded-xl border border-slate-200 p-4">
    <div class="flex items-center gap-3 mb-2">
      <span class="material-symbols-outlined text-primary">event</span>
      <span class="text-sm font-bold text-slate-900">Issue Date</span>
    </div>
    <p class="text-lg font-bold text-primary"><?php echo date('Y-m-d', $latestCrlInfo['lastUpdate']); ?></p>
    <p class="text-xs text-slate-500 mt-1">Current CRL issued</p>
  </div>

  <!-- Renewal Due -->
  <div class="bg-white rounded-xl border <?php echo $daysUntilRenewal <= 0 ? 'border-amber-300 bg-amber-50' : 'border-slate-200'; ?> p-4">
    <div class="flex items-center gap-3 mb-2">
      <span class="material-symbols-outlined <?php echo $daysUntilRenewal <= 0 ? 'text-amber-500' : 'text-slate-500'; ?>">update</span>
      <span class="text-sm font-bold text-slate-900">Renewal Due</span>
    </div>
    <p class="text-lg font-bold <?php echo $daysUntilRenewal <= 0 ? 'text-amber-600' : 'text-slate-700'; ?>">
      <?php echo date('Y-m-d', $latestCrlInfo['nextUpdate'] - ($CRL_RENEW_BEFORE * 86400)); ?>
    </p>
    <p class="text-xs text-slate-500 mt-1">
      <?php if ($daysUntilRenewal <= 0): ?>
        <span class="text-amber-600 font-medium">Renewal required now!</span>
      <?php else: ?>
        <?php echo $daysUntilRenewal; ?> days until renewal
      <?php endif; ?>
    </p>
  </div>

  <!-- Expiry Date -->
  <div class="bg-white rounded-xl border <?php echo $daysUntilExpiry <= 0 ? 'border-red-300 bg-red-50' : 'border-slate-200'; ?> p-4">
    <div class="flex items-center gap-3 mb-2">
      <span class="material-symbols-outlined <?php echo $daysUntilExpiry <= 0 ? 'text-red-500' : 'text-slate-500'; ?>">event_busy</span>
      <span class="text-sm font-bold text-slate-900">Expiry Date</span>
    </div>
    <p class="text-lg font-bold <?php echo $daysUntilExpiry <= 0 ? 'text-red-600' : 'text-slate-700'; ?>">
      <?php echo date('Y-m-d', $latestCrlInfo['nextUpdate']); ?>
    </p>
    <p class="text-xs text-slate-500 mt-1">
      <?php if ($daysUntilExpiry <= 0): ?>
        <span class="text-red-600 font-medium">CRL has expired!</span>
      <?php else: ?>
        <?php echo $daysUntilExpiry; ?> days remaining
      <?php endif; ?>
    </p>
  </div>
</div>
<?php else: ?>
<!-- No CRL Found -->
<div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
  <div class="flex items-start gap-3">
    <span class="material-symbols-outlined text-amber-600">warning</span>
    <div>
      <p class="font-bold text-slate-900">CRL Not Found</p>
      <p class="text-sm text-slate-600 mt-1">Could not find or parse the current CRL file. Please check the CRL directory.</p>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Current CRL File -->
<div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-3">
      <span class="material-symbols-outlined text-green-600">verified</span>
      <div>
        <p class="font-bold text-slate-900">Current CRL File</p>
        <p class="text-sm text-slate-600 font-mono">kisti-ca-v3.crl</p>
      </div>
    </div>
    <a href="<?php echo $crlDownloadUrl; ?>kisti-ca-v3.crl"
       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
      <span class="material-symbols-outlined text-sm">download</span>
      Download
    </a>
  </div>
  <p class="text-xs text-slate-500 mt-2">This file is always the latest CRL. Versioned files below are archives.</p>
</div>

<!-- CRL History (Archives) -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="px-4 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
    <h3 class="font-bold text-slate-900 flex items-center gap-2">
      <span class="material-symbols-outlined text-sm text-slate-500">history</span>
      CRL Archives
    </h3>
    <span class="text-sm text-slate-500">Total: <strong class="text-slate-900"><?php echo $total; ?></strong> files</span>
  </div>

  <?php if (count($pagedFiles) == 0): ?>
  <div class="p-8 text-center">
    <span class="material-symbols-outlined text-slate-300 text-4xl mb-2">folder_off</span>
    <p class="text-slate-500 text-sm">No CRL archive files found</p>
  </div>
  <?php else: ?>

  <!-- Desktop Table -->
  <div class="hidden md:block overflow-x-auto">
    <table class="w-full">
      <thead class="bg-slate-50 border-b border-slate-200">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">CRL Number</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Filename</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Size</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Issue Date</th>
          <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Download</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($pagedFiles as $idx => $crl): ?>
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3">
            <span class="font-mono font-bold text-slate-700"><?php echo $crl['serialHex']; ?></span>
          </td>
          <td class="px-4 py-3 font-mono text-sm text-slate-600"><?php echo htmlspecialchars($crl['filename']); ?></td>
          <td class="px-4 py-3 text-sm text-slate-500"><?php echo number_format($crl['size']); ?> bytes</td>
          <td class="px-4 py-3 text-sm text-slate-500"><?php echo date('Y-m-d H:i', $crl['issueDate']); ?></td>
          <td class="px-4 py-3 text-center">
            <a href="<?php echo $crlDownloadUrl . htmlspecialchars($crl['filename']); ?>"
               class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1 rounded transition-all inline-flex items-center gap-1">
              <span class="material-symbols-outlined text-xs">download</span>
              Download
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Mobile Cards -->
  <div class="md:hidden divide-y divide-slate-100">
    <?php foreach ($pagedFiles as $idx => $crl): ?>
    <div class="p-4">
      <div class="flex justify-between items-start mb-2">
        <div>
          <span class="font-mono font-bold text-slate-700">#<?php echo $crl['serialHex']; ?></span>
        </div>
        <a href="<?php echo $crlDownloadUrl . htmlspecialchars($crl['filename']); ?>"
           class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-1 rounded transition-all">
          Download
        </a>
      </div>
      <p class="text-xs text-slate-500 font-mono"><?php echo htmlspecialchars($crl['filename']); ?></p>
      <div class="flex items-center justify-between mt-2 text-xs text-slate-400">
        <span><?php echo number_format($crl['size']); ?> bytes</span>
        <span>Issued: <?php echo date('Y-m-d H:i', $crl['issueDate']); ?></span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($total > $ipp): ?>
<div class="flex justify-center gap-2 mb-6">
  <?php if ($page > 1): ?>
  <a href="?page=<?php echo $page - 1; ?>" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 rounded text-sm text-slate-700">Prev</a>
  <?php endif; ?>
  <span class="px-3 py-1 text-sm text-slate-500">Page <?php echo $page; ?> of <?php echo $last; ?></span>
  <?php if ($page < $last): ?>
  <a href="?page=<?php echo $page + 1; ?>" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 rounded text-sm text-slate-700">Next</a>
  <?php endif; ?>
</div>
<?php endif; ?>

<!-- Info Box -->
<div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
  <div class="flex items-start gap-3">
    <span class="material-symbols-outlined text-blue-600">info</span>
    <div>
      <p class="font-bold text-slate-900">CRL Policy</p>
      <p class="text-sm text-slate-600 mt-1">
        KISTI CA issues a new Certificate Revocation List (CRL) every <strong><?php echo $CRL_VALIDITY_DAYS; ?> days</strong>.
        The CRL is renewed <strong><?php echo $CRL_RENEW_BEFORE; ?> days before</strong> the current CRL expires to ensure continuous availability.
      </p>
      <p class="text-sm text-slate-600 mt-2">
        <strong>File Naming:</strong>
      </p>
      <ul class="text-sm text-slate-600 mt-1 ml-4 list-disc space-y-1">
        <li><span class="font-mono">kisti-ca-v3.crl</span> - Always the current/latest CRL</li>
        <li><span class="font-mono">kisti-ca-v3.XXXX.crl</span> - Archived CRL (XXXX = CRL Number in hex)</li>
      </ul>
      <p class="text-sm text-slate-600 mt-2">
        CRL Distribution Point (Official): <a href="http://ca.gridcenter.or.kr/CRL/kisti-ca-v3.crl" target="_blank" class="text-primary hover:underline font-mono">http://ca.gridcenter.or.kr/CRL/kisti-ca-v3.crl</a>
      </p>
    </div>
  </div>
</div>

<?php
include("tail.php");
?>
