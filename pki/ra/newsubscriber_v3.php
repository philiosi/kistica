<?php
include("common_v3.php");

if ($mode == 'register') {
  $firstname = $form['firstname'];
  $lastname = $form['lastname'];
  $gender = $form['gender'];
  $country = $form['country'];
  $org = $form['org'];
  $orgunit = $form['orgunit'];
  $position = $form['position'];
  $email = $form['email'];

  $ra_cn = $_SERVER['SSL_CLIENT_S_DN_CN'];

  $qry = "INSERT INTO subscriber SET firstname='$firstname'"
     .",lastname='$lastname'"
     .",gender='$gender',country='$country',org='$org'"
     .",orgunit='$orgunit'"
     .",position='$position'"
     .",email='$email'"
     .",ra_cn='$ra_cn',idate=NOW()";
  $ret = DBQuery($qry);
  Redirect("/ra/subscribers_v3.php");
  exit;
}

include("head.php");

// Get existing organizations for dropdown
$orgs = array();
$qry = "SELECT org FROM subscriber GROUP BY org";
$ret = DBQuery($qry);
while ($row = DBFetchRow($ret)) {
  $orgs[] = $row['org'];
}
?>

<!-- Page Header -->
<div class="flex items-center gap-3 pb-4 mb-6 border-b-2 border-amber-500">
  <span class="material-symbols-outlined text-amber-500">person_add</span>
  <h1 class="text-xl font-bold text-slate-900">Register New Subscriber</h1>
</div>

<!-- Registration Form -->
<form action="<?php echo $env['self']; ?>" name="form" method="post" class="space-y-6">

  <!-- Personal Information Card -->
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
      <h3 class="font-bold text-slate-900 flex items-center gap-2">
        <span class="material-symbols-outlined text-sm text-slate-500">person</span>
        Personal Information
      </h3>
    </div>
    <div class="p-4 space-y-4">
      <!-- First Name -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">First Name <span class="text-red-500">*</span></label>
        <input type="text" name="firstname" required
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
          placeholder="Enter first name">
      </div>

      <!-- Last Name -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Last Name <span class="text-red-500">*</span></label>
        <input type="text" name="lastname" required
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
          placeholder="Enter last name">
      </div>

      <!-- Gender -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Gender</label>
        <div class="flex gap-4">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="gender" value="M"
              class="w-4 h-4 text-amber-500 border-slate-300 focus:ring-amber-500">
            <span class="text-sm text-slate-700">Male</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="gender" value="F"
              class="w-4 h-4 text-amber-500 border-slate-300 focus:ring-amber-500">
            <span class="text-sm text-slate-700">Female</span>
          </label>
        </div>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" required
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
          placeholder="user@example.com">
      </div>
    </div>
  </div>

  <!-- Organization Information Card -->
  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
      <h3 class="font-bold text-slate-900 flex items-center gap-2">
        <span class="material-symbols-outlined text-sm text-slate-500">corporate_fare</span>
        Organization Information
      </h3>
    </div>
    <div class="p-4 space-y-4">
      <!-- Country -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Country <span class="text-red-500">*</span></label>
        <select name="country" required
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all bg-white">
          <option value="Korea">Korea</option>
          <option value="China">China</option>
          <option value="Vietnam">Vietnam</option>
          <option value="Saudi Arabia">Saudi Arabia</option>
          <option value="Australia">Australia</option>
        </select>
      </div>

      <!-- Organization -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Organization <span class="text-red-500">*</span></label>
        <div class="flex flex-col md:flex-row gap-2">
          <input type="text" name="org" required
            class="flex-1 px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
            placeholder="Enter organization name">
          <select name="sel_org" onchange="select_org()"
            class="px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all bg-white">
            <option value="">Select existing...</option>
            <?php foreach ($orgs as $org): ?>
            <option value="<?php echo htmlspecialchars($org); ?>"><?php echo htmlspecialchars($org); ?></option>
            <?php endforeach; ?>
            <option value="_ETC_">Other (specify)</option>
          </select>
        </div>
        <p class="text-xs text-slate-400 mt-1">Select from existing organizations or enter a new one</p>
      </div>

      <!-- Organization Unit -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Organization Unit</label>
        <input type="text" name="orgunit"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
          placeholder="Department, Division, etc.">
      </div>

      <!-- Position -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Position</label>
        <input type="text" name="position"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all"
          placeholder="Job title or role">
      </div>
    </div>
  </div>

  <!-- Submit Button -->
  <input type="hidden" name="mode" value="register">
  <button type="submit"
    class="w-full bg-amber-500 hover:bg-amber-600 text-white py-4 rounded-xl font-bold text-base transition-all active:scale-[0.99] shadow-md flex items-center justify-center gap-2">
    <span class="material-symbols-outlined">person_add</span>
    Register Subscriber
  </button>
</form>

<!-- Back Link -->
<div class="mt-6 text-center">
  <a href="index.php" class="text-amber-600 hover:text-amber-700 text-sm font-medium inline-flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">arrow_back</span>
    Back to RA Home
  </a>
</div>

<script>
function select_org() {
  var sel = document.form.sel_org;
  var idx = sel.selectedIndex;
  var org = sel[idx].value;
  if (org == '_ETC_') {
    document.form.org.value = '';
    document.form.org.focus();
    return;
  }
  document.form.org.value = org;
}
</script>

<?php
include("tail.php");
?>
