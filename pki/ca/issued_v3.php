<?php
include("common_v3.php");
include("head.php");

// Search handling
$sql_where = ' WHERE 1';
$search = isset($form['search']) ? $form['search'] : '';
if ($search) {
  $sql_where .= " AND ((subject LIKE '%$search%'))";
}
$sql_where .= " AND status='issued'";

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

$qry = "SELECT * FROM cert $sql_where ORDER BY serial DESC LIMIT $start,$ipp";
$ret = DBQuery($qry);
$certs = array();
while ($row = DBFetchRow($ret)) {
  $certs[] = $row;
}
$prefix = "http://ca.gridcenter.or.kr/issued_v3/";
?>

<!-- Page Header -->
<div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-red-500">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-red-500">history</span>
    <h1 class="text-xl font-bold text-slate-900">Issued Certificates</h1>
  </div>
  <a href="http://ca.gridcenter.or.kr/issued_v3/" target="_blank" class="text-xs text-slate-500 hover:text-slate-700 flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">open_in_new</span>
    Public List
  </a>
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

<?php if (count($certs) == 0): ?>
<!-- Empty State -->
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
  <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">search_off</span>
  <p class="text-slate-500">No issued certificates found</p>
</div>
<?php else: ?>

<!-- Desktop Table -->
<div class="hidden md:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-slate-50 border-b border-slate-200">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">File</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Subject</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Type</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Valid Until</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($certs as $row):
          $serial = $row['serial'];
          $serial_h = sprintf("%02x", $serial);
          $ctype_s = ($row['ctype'] == 'user') ? 'person' : 'host';
          $vuntil_s = substr($row['vuntil'], 0, 10);
        ?>
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3">
            <a href="<?php echo $prefix . $serial_h; ?>.pem" class="text-red-600 hover:underline font-mono text-sm" target="_blank">
              <?php echo $serial_h; ?>.pem
            </a>
          </td>
          <td class="px-4 py-3">
            <a href="<?php echo $prefix . $serial_h; ?>.txt" class="text-slate-900 hover:text-red-600 text-sm" target="_blank">
              <?php echo htmlspecialchars($row['subject']); ?>
            </a>
          </td>
          <td class="px-4 py-3">
            <span class="text-xs px-2 py-1 rounded <?php echo $ctype_s == 'person' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'; ?>">
              <?php echo $ctype_s; ?>
            </span>
          </td>
          <td class="px-4 py-3 text-sm text-slate-500"><?php echo $vuntil_s; ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3 mb-6">
  <?php foreach ($certs as $row):
    $serial = $row['serial'];
    $serial_h = sprintf("%02x", $serial);
    $ctype_s = ($row['ctype'] == 'user') ? 'person' : 'host';
    $vuntil_s = substr($row['vuntil'], 0, 10);
  ?>
  <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
    <div class="flex justify-between items-start mb-2">
      <a href="<?php echo $prefix . $serial_h; ?>.pem" class="font-mono text-xs text-red-600" target="_blank">
        <?php echo $serial_h; ?>.pem
      </a>
      <span class="text-[10px] px-2 py-0.5 rounded <?php echo $ctype_s == 'person' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'; ?> font-bold">
        <?php echo $ctype_s; ?>
      </span>
    </div>
    <a href="<?php echo $prefix . $serial_h; ?>.txt" class="text-sm text-slate-900 block mb-2 break-all" target="_blank">
      <?php echo htmlspecialchars($row['subject']); ?>
    </a>
    <div class="text-xs text-slate-500">
      Valid until: <?php echo $vuntil_s; ?>
    </div>
  </div>
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
