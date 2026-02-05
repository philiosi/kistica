<?php
include("common_v3.php");

##############################################################
### modes {{{
##############################################################

if ($mode == 'mail1') {
  $id = $form['id'];
  $qry = "SELECT * FROM subscriber WHERE id='$id'";
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);

  include("head.php");
  print<<<EOS
$row[firstname]
$row[lastname]
EOS;
  include("tail.php");
  exit;


// personal no 생성
} else if ($mode == 'getperid') {
  $id = $form['id'];
  $qry = "SELECT * FROM subscriber WHERE id='$id'";
  $row = DBQueryAndFetchRow($qry);

  while (1) {
    $perid = rand(10000000,99999999); # 8-digit random number
    $qry = "SELECT * FROM subscriber WHERE perid='$perid'";
    $row = DBQueryAndFetchRow($qry);
    if ($row) continue;
    else break;
  }

  $qry = "UPDATE subscriber SET perid='$perid' WHERE id='$id'";
  $row = DBQuery($qry);

  Redirect("$env[self]?mode=info&id=$id");
  exit;

// 삭제
} else if ($mode == 'del') {
  $id = $form['id'];
  $qry = "DELETE FROM subscriber WHERE id='$id'";
  $ret = DBQuery($qry);
  Redirect("$env[self]");
  exit;


// 수정 처리
} else if ($mode == 'doedit') {
  $country  = $form['country'];
  $org      = $form['org'];
  $orgunit  = $form['orgunit'];
  $position = $form['position'];
  $email    = $form['email'];
  $id = $form['id'];

  $qry = "UPDATE subscriber SET country='$country', org='$org', orgunit='$orgunit'"
      .", position='$position', email='$email'"
      ." WHERE id='$id'";
  $ret = mysql_query($qry);
  print mysql_error();

  Redirect("$env[self]?mode=info&id=$id");
  exit;

// 상세보기 / 수정
} else if ($mode == 'info' or $mode == 'edit') {
  $id = $form['id'];
  include("head.php");

  $qry = "SELECT * FROM subscriber WHERE id='$id'";
  $row = DBQueryAndFetchRow($qry);
  $email = $row['email'];
  $fn = $row['firstname'];
  $ln = $row['lastname'];
?>

<!-- Page Header -->
<div class="flex items-center gap-3 pb-4 mb-6 border-b-2 border-red-500">
  <span class="material-symbols-outlined text-red-500">person</span>
  <h1 class="text-xl font-bold text-slate-900">Subscriber Information</h1>
</div>

<?php if ($mode == 'info'): ?>

<!-- Subscriber Profile Card -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="bg-gradient-to-r from-red-500 to-red-600 px-4 py-6 text-center">
    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
      <span class="material-symbols-outlined text-white text-3xl">person</span>
    </div>
    <h2 class="text-white text-xl font-bold"><?php echo htmlspecialchars($fn . ' ' . $ln); ?></h2>
    <p class="text-white/80 text-sm mt-1"><?php echo htmlspecialchars($row['position']); ?></p>
  </div>

  <div class="p-4 space-y-3">
    <!-- ID -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">ID</span>
      <span class="text-slate-900 font-mono font-medium"><?php echo htmlspecialchars($row['id']); ?></span>
    </div>

    <!-- Personal No -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Personal No.</span>
      <?php if (empty($row['perid'])): ?>
        <a href="<?php echo $env['self']; ?>?mode=getperid&id=<?php echo $id; ?>"
           class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm font-bold transition-all">
          Assign Now
        </a>
      <?php else: ?>
        <span class="text-slate-900 font-mono font-medium"><?php echo htmlspecialchars($row['perid']); ?></span>
      <?php endif; ?>
    </div>

    <!-- Country -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Country</span>
      <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['country']); ?></span>
    </div>

    <!-- Organization -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Organization</span>
      <span class="text-slate-900 font-medium text-right"><?php echo htmlspecialchars($row['org']); ?></span>
    </div>

    <!-- Organization Unit -->
    <?php if (!empty($row['orgunit'])): ?>
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Org. Unit</span>
      <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['orgunit']); ?></span>
    </div>
    <?php endif; ?>

    <!-- Position -->
    <?php if (!empty($row['position'])): ?>
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Position</span>
      <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['position']); ?></span>
    </div>
    <?php endif; ?>

    <!-- Email -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Email</span>
      <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="text-red-600 font-medium"><?php echo htmlspecialchars($email); ?></a>
    </div>

    <!-- Registration Date -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Registered</span>
      <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['idate']); ?></span>
    </div>

    <!-- RA -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Charging RA</span>
      <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['ra_cn']); ?></span>
    </div>

    <!-- PIN -->
    <div class="flex justify-between items-center py-3">
      <span class="text-xs text-slate-500 uppercase tracking-wider">PIN #</span>
      <?php if (empty($row['pin'])): ?>
        <span class="text-slate-400 text-sm italic">Not yet assigned</span>
      <?php else: ?>
        <span class="text-slate-900 font-mono font-bold text-lg bg-red-50 px-3 py-1 rounded"><?php echo htmlspecialchars($row['pin']); ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Action Buttons -->
<div class="grid grid-cols-2 gap-3 mb-6">
  <a href="<?php echo $env['self']; ?>?mode=edit&id=<?php echo $id; ?>"
     class="flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-xl font-bold transition-all">
    <span class="material-symbols-outlined text-sm">edit</span>
    Edit
  </a>
  <button onclick="handleDelete(<?php echo $id; ?>)"
     class="flex items-center justify-center gap-2 bg-slate-500 hover:bg-slate-600 text-white py-3 rounded-xl font-bold transition-all">
    <span class="material-symbols-outlined text-sm">delete</span>
    Delete
  </button>
</div>

<!-- CA Actions -->
<?php $subscid = $row['id']; $pin = $row['pin']; ?>
<?php if (!empty($pin)): ?>
<div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
  <h3 class="font-bold text-slate-900 mb-3 flex items-center gap-2">
    <span class="material-symbols-outlined text-blue-500">admin_panel_settings</span>
    CA Actions
  </h3>
  <a href="wacc_v3.php?mode=add&pin=<?php echo $pin; ?>&email=<?php echo $email; ?>&cn=<?php echo $email; ?>&subscid=<?php echo $subscid; ?>"
     class="flex gap-4 bg-white p-4 rounded-xl border border-blue-200 shadow-sm transition-all active:scale-[0.99]">
    <div class="flex items-center justify-center rounded-lg bg-blue-500 shrink-0 w-12 h-12 text-white">
      <span class="material-symbols-outlined">badge</span>
    </div>
    <div class="flex flex-1 flex-col justify-center">
      <p class="text-slate-900 font-bold">Register WACC</p>
      <p class="text-slate-500 text-xs">Add to Web Access Client Certificate list</p>
    </div>
    <div class="shrink-0 flex items-center">
      <span class="material-symbols-outlined text-slate-300">chevron_right</span>
    </div>
  </a>
</div>
<?php endif; ?>

<!-- Memo -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
    <h3 class="font-bold text-slate-900 flex items-center gap-2">
      <span class="material-symbols-outlined text-sm text-slate-500">note</span>
      Memo
    </h3>
  </div>
  <div class="p-4">
    <form action="<?php echo $env['self']; ?>" method="post" class="flex gap-2">
      <input type="text" name="memo" value="<?php echo htmlspecialchars($row['memo']); ?>"
        class="flex-1 px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-red-500"
        placeholder="Add a memo...">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <input type="hidden" name="mode" value="savememo">
      <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-all">
        Save
      </button>
    </form>
  </div>
</div>

<!-- Subscriber's Certificates -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
    <h3 class="font-bold text-slate-900 flex items-center gap-2">
      <span class="material-symbols-outlined text-sm text-slate-500">verified</span>
      Subscriber's Certificates
    </h3>
  </div>

  <?php
  $qry = "SELECT * FROM cert"
     ." LEFT JOIN csr ON cert.csrid=csr.csrid"
     ." WHERE csr.email='$email'"
     ." ORDER BY cert.idate DESC";
  $ret = DBQuery($qry);
  $certs = array();
  while ($certrow = DBFetchRow($ret)) {
    $certs[] = $certrow;
  }
  ?>

  <?php if (count($certs) == 0): ?>
  <div class="p-8 text-center">
    <span class="material-symbols-outlined text-slate-300 text-4xl mb-2">badge</span>
    <p class="text-slate-500 text-sm">No certificates found for this subscriber</p>
  </div>
  <?php else: ?>

  <!-- Desktop Table -->
  <div class="hidden md:block overflow-x-auto">
    <table class="w-full">
      <thead class="bg-slate-50 border-b border-slate-200">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Serial</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Subject</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">From</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Until</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($certs as $certrow): ?>
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 font-mono text-sm"><?php echo htmlspecialchars($certrow['serial']); ?></td>
          <td class="px-4 py-3 text-sm text-slate-600"><?php echo htmlspecialchars($certrow['subject']); ?></td>
          <td class="px-4 py-3 text-sm text-slate-500"><?php echo htmlspecialchars(substr($certrow['vfrom'], 0, 10)); ?></td>
          <td class="px-4 py-3 text-sm text-slate-500"><?php echo htmlspecialchars(substr($certrow['vuntil'], 0, 10)); ?></td>
          <td class="px-4 py-3">
            <?php
            $status = $certrow['status'];
            $statusClass = 'bg-slate-100 text-slate-700';
            if ($status == 'issued') $statusClass = 'bg-green-100 text-green-700';
            elseif ($status == 'revoked') $statusClass = 'bg-red-100 text-red-700';
            ?>
            <span class="text-xs px-2 py-1 rounded <?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></span>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Mobile Cards -->
  <div class="md:hidden divide-y divide-slate-100">
    <?php foreach ($certs as $certrow): ?>
    <div class="p-4">
      <div class="flex justify-between items-start mb-2">
        <span class="font-mono text-sm text-red-600">#<?php echo htmlspecialchars($certrow['serial']); ?></span>
        <?php
        $status = $certrow['status'];
        $statusClass = 'bg-slate-100 text-slate-700';
        if ($status == 'issued') $statusClass = 'bg-green-100 text-green-700';
        elseif ($status == 'revoked') $statusClass = 'bg-red-100 text-red-700';
        ?>
        <span class="text-[10px] px-2 py-0.5 rounded <?php echo $statusClass; ?> font-bold"><?php echo htmlspecialchars($status); ?></span>
      </div>
      <p class="text-sm text-slate-900 mb-2 break-all"><?php echo htmlspecialchars($certrow['subject']); ?></p>
      <p class="text-xs text-slate-500"><?php echo htmlspecialchars(substr($certrow['vfrom'], 0, 10)); ?> ~ <?php echo htmlspecialchars(substr($certrow['vuntil'], 0, 10)); ?></p>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<!-- Back Link -->
<div class="text-center">
  <a href="subscribers_v3.php" class="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">arrow_back</span>
    Back to Subscribers List
  </a>
</div>

<script>
function handleDelete(id) {
  if (confirm('Are you sure you want to delete this subscriber?')) {
    window.location.href = '<?php echo $env['self']; ?>?mode=del&id=' + id;
  }
}
</script>

<?php
  include("tail.php");
  exit;

// Edit Mode
else: ?>

<!-- Edit Form -->
<form action="<?php echo $env['self']; ?>" name="form2" method="post" class="space-y-6">

  <!-- Profile Header -->
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-6 text-center">
      <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
        <span class="material-symbols-outlined text-white text-3xl">edit</span>
      </div>
      <h2 class="text-white text-xl font-bold"><?php echo htmlspecialchars($fn . ' ' . $ln); ?></h2>
      <p class="text-white/80 text-sm mt-1">Edit Subscriber Information</p>
    </div>

    <div class="p-4 space-y-4">
      <!-- Read-only fields -->
      <div class="flex justify-between items-center py-3 border-b border-slate-100">
        <span class="text-xs text-slate-500 uppercase tracking-wider">ID</span>
        <span class="text-slate-900 font-mono font-medium"><?php echo htmlspecialchars($row['id']); ?></span>
      </div>

      <div class="flex justify-between items-center py-3 border-b border-slate-100">
        <span class="text-xs text-slate-500 uppercase tracking-wider">Personal No.</span>
        <span class="text-slate-900 font-mono font-medium"><?php echo htmlspecialchars($row['perid']); ?></span>
      </div>

      <div class="flex justify-between items-center py-3 border-b border-slate-100">
        <span class="text-xs text-slate-500 uppercase tracking-wider">Name</span>
        <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($fn . ' ' . $ln); ?></span>
      </div>

      <!-- Editable fields -->
      <div class="pt-2">
        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-1">Country</label>
        <input type="text" name="country" value="<?php echo htmlspecialchars($row['country']); ?>"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
      </div>

      <div>
        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-1">Organization</label>
        <input type="text" name="org" value="<?php echo htmlspecialchars($row['org']); ?>"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
      </div>

      <div>
        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-1">Organization Unit</label>
        <input type="text" name="orgunit" value="<?php echo htmlspecialchars($row['orgunit']); ?>"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
      </div>

      <div>
        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-1">Position</label>
        <input type="text" name="position" value="<?php echo htmlspecialchars($row['position']); ?>"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
      </div>

      <div>
        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-1">Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
      </div>

      <!-- Read-only fields -->
      <div class="flex justify-between items-center py-3 border-t border-slate-100">
        <span class="text-xs text-slate-500 uppercase tracking-wider">Registered</span>
        <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['idate']); ?></span>
      </div>

      <div class="flex justify-between items-center py-3 border-b border-slate-100">
        <span class="text-xs text-slate-500 uppercase tracking-wider">Charging RA</span>
        <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($row['ra_cn']); ?></span>
      </div>
    </div>
  </div>

  <!-- Submit Buttons -->
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  <input type="hidden" name="mode" value="doedit">

  <div class="grid grid-cols-2 gap-3">
    <a href="<?php echo $env['self']; ?>?mode=info&id=<?php echo $id; ?>"
       class="flex items-center justify-center gap-2 bg-slate-500 hover:bg-slate-600 text-white py-3 rounded-xl font-bold transition-all">
      <span class="material-symbols-outlined text-sm">close</span>
      Cancel
    </a>
    <button type="submit"
       class="flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-xl font-bold transition-all">
      <span class="material-symbols-outlined text-sm">save</span>
      Save
    </button>
  </div>
</form>

<?php
  include("tail.php");
  exit;
endif;

// 메모저장
} else if ($mode == 'savememo') {
  $id = $form['id'];
  $memo = $form['memo'];

  $qry = "UPDATE subscriber SET memo='$memo' WHERE id='$id'";
  $ret = DBQuery($qry);

  Redirect("$env[self]?mode=info&id=$id");
  exit;
}


### modes }}}


// List View (Default)
include("head.php");

$search = isset($form['search']) ? $form['search'] : '';

$sql_where = '';
if ($search) {
  $sql_where = "WHERE ((s.firstname LIKE '%$search%') OR (s.lastname LIKE '%$search%')"
    ." OR (s.org LIKE '%$search%') OR (s.email LIKE '%$search%')"
    ." OR (s.memo LIKE '%$search%')"
    .")";
}

$qry = "SELECT count(*) as count FROM subscriber s $sql_where";
$ret = mysql_query($qry);
$row = mysql_fetch_array($ret);
$total = $row['count'];

$ipp = 20;
$page = isset($form['page']) ? $form['page'] : 1;
if ($page == '') $page = 1;
$last = ceil($total/$ipp);
if ($last == 0) $last = 1;
if ($page > $last) $page = $last;
$start = ($page-1) * $ipp;

unset($form['page']);
$qs = Qstr($form);
$url = "$env[self]$qs";
$pager = Pager_s($url, $page, $total, $ipp);
?>

<!-- Page Header -->
<div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-red-500">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-red-500">group</span>
    <h1 class="text-xl font-bold text-slate-900">Subscribers</h1>
  </div>
</div>

<!-- Search & Stats -->
<div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
  <form action="<?php echo $env['self']; ?>" method="get" class="flex flex-col md:flex-row gap-4 items-center">
    <div class="flex-1 w-full">
      <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
        placeholder="Search by name, org, email, memo..."
        class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-red-500">
    </div>
    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition-all">
      Search
    </button>
  </form>
  <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
    <span>Total: <strong class="text-slate-900"><?php echo $total; ?></strong> subscribers</span>
    <span>Page <?php echo $page; ?> / <?php echo $last; ?></span>
  </div>
</div>

<?php
$qry = "SELECT s.*,w.serial FROM subscriber s"
  ." LEFT JOIN webcert w ON s.id=w.subscid"
  ." $sql_where ORDER BY s.idate DESC";
$qry .= " LIMIT $start,$ipp";

$ret = mysql_query($qry);
$subscribers = array();
while ($row = mysql_fetch_array($ret)) {
  $subscribers[] = $row;
}
?>

<?php if (count($subscribers) == 0): ?>
<!-- Empty State -->
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
  <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">search_off</span>
  <p class="text-slate-500">No subscribers found</p>
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
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Name</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Organization</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Email</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Date</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Memo</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($subscribers as $row):
          $id = $row['id'];
          $idate = substr($row['idate'], 0, 10);
          $email = $row['email'];
          $fn = $row['firstname'];
          $ln = $row['lastname'];
          $serial = $row['serial'];
        ?>
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3">
            <a href="<?php echo $env['self']; ?>?mode=info&id=<?php echo $id; ?>" class="text-red-600 font-medium hover:underline"><?php echo $id; ?></a>
          </td>
          <td class="px-4 py-3">
            <?php if (!empty($serial)): ?>
              <span class="font-mono text-sm"><?php echo htmlspecialchars($serial); ?></span>
            <?php else: ?>
              <span class="text-slate-400">-</span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3 font-medium text-slate-900"><?php echo htmlspecialchars($fn . ' ' . $ln); ?></td>
          <td class="px-4 py-3 text-slate-600 text-sm"><?php echo htmlspecialchars($row['org']); ?></td>
          <td class="px-4 py-3 text-sm">
            <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="text-red-600 hover:underline"><?php echo htmlspecialchars($email); ?></a>
          </td>
          <td class="px-4 py-3 text-slate-500 text-sm whitespace-nowrap"><?php echo $idate; ?></td>
          <td class="px-4 py-3 text-slate-500 text-sm"><?php echo htmlspecialchars($row['memo']); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3 mb-6">
  <?php foreach ($subscribers as $row):
    $id = $row['id'];
    $idate = substr($row['idate'], 0, 10);
    $email = $row['email'];
    $fn = $row['firstname'];
    $ln = $row['lastname'];
    $serial = $row['serial'];
  ?>
  <a href="<?php echo $env['self']; ?>?mode=info&id=<?php echo $id; ?>"
     class="block bg-white rounded-xl border border-slate-200 p-4 shadow-sm active:scale-[0.99] transition-all">
    <div class="flex justify-between items-start mb-2">
      <div>
        <p class="font-bold text-slate-900"><?php echo htmlspecialchars($fn . ' ' . $ln); ?></p>
        <p class="text-sm text-slate-500"><?php echo htmlspecialchars($row['org']); ?></p>
      </div>
      <span class="text-xs text-slate-400">#<?php echo $id; ?></span>
    </div>
    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
      <span class="text-xs text-slate-500"><?php echo htmlspecialchars($email); ?></span>
      <span class="text-xs text-slate-400"><?php echo $idate; ?></span>
    </div>
    <?php if (!empty($serial)): ?>
    <div class="mt-2">
      <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">
        WACC: <?php echo htmlspecialchars($serial); ?>
      </span>
    </div>
    <?php endif; ?>
    <?php if (!empty($row['memo'])): ?>
    <div class="mt-2 text-xs text-slate-400 italic"><?php echo htmlspecialchars($row['memo']); ?></div>
    <?php endif; ?>
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
