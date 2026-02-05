<?php
header('Content-Type: text/html; charset=utf-8');
include("common_v3.php");

// Generate PIN action
if ($mode == 'getpin') {
  $id = $form['id'];
  $pin = rand(10000000, 90000000);
  $qry = "UPDATE subscriber SET pin='$pin' WHERE id='$id'";
  $ret = DBQuery($qry);
  Redirect("$env[self]?mode=info&id=$id");
  exit;
}

// Subscriber Detail View
if ($mode == 'info') {
  $id = $form['id'];
  include("head.php");

  $qry = "SELECT * FROM subscriber WHERE id='$id'";
  $row = DBQueryAndFetchRow($qry);
  $email = $row['email'];
  $pin = $row['pin'];
  $perid = $row['perid'];
?>

<!-- Page Header -->
<div class="flex items-center gap-3 pb-4 mb-6 border-b-2 border-amber-500">
  <span class="material-symbols-outlined text-amber-500">person</span>
  <h1 class="text-xl font-bold text-slate-900">Subscriber Information</h1>
</div>

<!-- Subscriber Profile Card -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-4 py-6 text-center">
    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
      <span class="material-symbols-outlined text-white text-3xl">person</span>
    </div>
    <h2 class="text-white text-xl font-bold"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></h2>
    <p class="text-white/80 text-sm mt-1"><?php echo htmlspecialchars($row['position']); ?></p>
  </div>

  <div class="p-4 space-y-3">
    <!-- Personal No -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Personal No.</span>
      <?php if (empty($perid)): ?>
        <span class="text-slate-400 text-sm italic">Not yet assigned by CA</span>
      <?php else: ?>
        <span class="text-slate-900 font-mono font-medium"><?php echo htmlspecialchars($perid); ?></span>
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

    <!-- Email -->
    <div class="flex justify-between items-center py-3 border-b border-slate-100">
      <span class="text-xs text-slate-500 uppercase tracking-wider">Email</span>
      <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="text-amber-600 font-medium"><?php echo htmlspecialchars($email); ?></a>
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
      <?php if (empty($pin)): ?>
        <a href="<?php echo $env['self']; ?>?mode=getpin&id=<?php echo $id; ?>"
           class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all">
          Generate PIN
        </a>
      <?php else: ?>
        <span class="text-slate-900 font-mono font-bold text-lg bg-amber-50 px-3 py-1 rounded"><?php echo htmlspecialchars($pin); ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if ($env['role'] == 'CA' && !empty($pin)): ?>
<!-- CA Actions -->
<div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
  <h3 class="font-bold text-slate-900 mb-3 flex items-center gap-2">
    <span class="material-symbols-outlined text-blue-500">admin_panel_settings</span>
    CA Actions
  </h3>
  <a href="wacc.php?mode=add&pin=<?php echo $pin; ?>&email=<?php echo $email; ?>&cn=<?php echo $email; ?>&subscid=<?php echo $row['id']; ?>"
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

<!-- Back Link -->
<div class="text-center">
  <a href="subscribers_v3.php" class="text-amber-600 hover:text-amber-700 text-sm font-medium inline-flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">arrow_back</span>
    Back to Subscribers List
  </a>
</div>

<?php
  include("tail.php");
  exit;
}

// Subscribers List View (Default)
include("head.php");
?>

<!-- Page Header -->
<div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-amber-500">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-amber-500">group</span>
    <h1 class="text-xl font-bold text-slate-900">Subscribers</h1>
  </div>
  <a href="newsubscriber_v3.php" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">add</span>
    New
  </a>
</div>

<?php
$qry = "SELECT * FROM subscriber ORDER BY idate DESC";
$ret = DBQuery($qry);
$subscribers = array();
while ($row = DBFetchRow($ret)) {
  $subscribers[] = $row;
}
?>

<?php if (count($subscribers) == 0): ?>
<!-- Empty State -->
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
  <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">group_off</span>
  <p class="text-slate-500">No subscribers registered yet</p>
  <a href="newsubscriber_v3.php" class="mt-4 inline-flex items-center gap-2 text-amber-600 font-medium">
    <span class="material-symbols-outlined text-sm">add</span>
    Register first subscriber
  </a>
</div>
<?php else: ?>

<!-- Desktop Table View -->
<div class="hidden md:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
  <table class="w-full">
    <thead class="bg-slate-50 border-b border-slate-200">
      <tr>
        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">ID</th>
        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Organization</th>
        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
        <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Personal No.</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
      <?php foreach ($subscribers as $row):
        $id = $row['id'];
        $idate = substr($row['idate'], 0, 10);
        $org = $row['org'];
        if (!empty($row['orgunit'])) {
          $org .= ' / ' . $row['orgunit'];
        }
      ?>
      <tr class="hover:bg-slate-50 transition-colors">
        <td class="px-4 py-3">
          <a href="<?php echo $env['self']; ?>?mode=info&id=<?php echo $id; ?>" class="text-amber-600 font-medium hover:underline"><?php echo $id; ?></a>
        </td>
        <td class="px-4 py-3 font-medium text-slate-900"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
        <td class="px-4 py-3 text-slate-600 text-sm"><?php echo htmlspecialchars($org); ?></td>
        <td class="px-4 py-3 text-slate-600 text-sm"><?php echo htmlspecialchars($row['email']); ?></td>
        <td class="px-4 py-3 text-slate-500 text-sm"><?php echo $idate; ?></td>
        <td class="px-4 py-3">
          <?php if (!empty($row['perid'])): ?>
            <span class="font-mono text-sm"><?php echo htmlspecialchars($row['perid']); ?></span>
          <?php else: ?>
            <span class="text-slate-400 text-sm">-</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Mobile Card View -->
<div class="md:hidden space-y-3">
  <?php foreach ($subscribers as $row):
    $id = $row['id'];
    $idate = substr($row['idate'], 0, 10);
  ?>
  <a href="<?php echo $env['self']; ?>?mode=info&id=<?php echo $id; ?>"
     class="block bg-white rounded-xl border border-slate-200 p-4 shadow-sm active:scale-[0.99] transition-all">
    <div class="flex justify-between items-start mb-2">
      <div>
        <p class="font-bold text-slate-900"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></p>
        <p class="text-sm text-slate-500"><?php echo htmlspecialchars($row['org']); ?></p>
      </div>
      <span class="text-xs text-slate-400">#<?php echo $id; ?></span>
    </div>
    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
      <span class="text-xs text-slate-500"><?php echo htmlspecialchars($row['email']); ?></span>
      <span class="text-xs text-slate-400"><?php echo $idate; ?></span>
    </div>
    <?php if (!empty($row['perid'])): ?>
    <div class="mt-2">
      <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">
        ID: <?php echo htmlspecialchars($row['perid']); ?>
      </span>
    </div>
    <?php endif; ?>
  </a>
  <?php endforeach; ?>
</div>

<?php endif; ?>

<?php
include("tail.php");
?>
