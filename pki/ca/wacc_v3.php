<?php
include("common_v3.php");

if ($mode == 'doadd' or $mode == 'doedit') {

  $cn = $form['cn'];
  $serial = $form['serial'];
  $pin = $form['pin'];
  $dn = $form['dn'];
  $email = $form['email'];
  $subscid = $form['subscid'];

  if ($cn == '') iError("CN field is null");
  if ($pin == '') iError("PIN field is null");
  if ($serial == '') iError("Serial field is null");

  $authz = $form['authz'];

  if ($mode == 'doadd') {
    $qry = "INSERT INTO webcert SET"
         ." cn='$cn',serial='$serial',pin='$pin'"
         .",dn='$dn'"
         .",authz='$authz'"
         .",email='$email'"
         .",subscid='$subscid'"
         .",idate=NOW(),udate=NOW()";
    $ret = DBQuery($qry);

  } else if ($mode == 'doedit') {
    $id = $form['id'];
    $qry = "UPDATE webcert SET"
         ." cn='$cn',serial='$serial',pin='$pin'"
         .",dn='$dn'"
         .",authz='$authz'"
         .",email='$email'"
         .",udate=NOW() WHERE id='$id'";
    $ret = DBQuery($qry);
  }

  Redirect("$env[self]?".time());
  exit;

// 삭제
} else if ($mode == 'del') {
  $id = $form['id'];
  $qry = "DELETE FROM webcert WHERE id='$id'";
  $ret = DBQuery($qry);
  Redirect("$env[self]?".time());


// 추가/수정
} else if ($mode == 'add' or $mode == 'edit') {

  include("head.php");

  if ($mode == 'add') {
    $qry = "SELECT MAX(serial) AS max FROM webcert";
    $row = DBQueryAndFetchRow($qry);
    $serial = $row['max'] + 1;

    $cn = isset($form['cn']) ? $form['cn'] : '';
    $pin = isset($form['pin']) ? $form['pin'] : '';
    $email = isset($form['email']) ? $form['email'] : '';
    $subscid = isset($form['subscid']) ? $form['subscid'] : '';
    $dn = '';
    $id = '';
    $row = array('authz' => 'user');

  } else if ($mode == 'edit') {
    $id = $form['id'];
    $qry = "SELECT * FROM webcert WHERE id='$id'";
    $row = DBQueryAndFetchRow($qry);
    $serial = $row['serial'];
    $cn = $row['cn'];
    $dn = $row['dn'];
    $pin = $row['pin'];
    $email = $row['email'];
    $subscid = $row['subscid'];
  }
?>

<!-- Page Header -->
<div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-red-500">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-red-500">badge</span>
    <h1 class="text-xl font-bold text-slate-900"><?php echo $mode == 'add' ? 'Add WACC' : 'Edit WACC'; ?></h1>
  </div>
  <a href="<?php echo $env['self']; ?>" class="text-slate-500 hover:text-slate-700 text-sm flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">arrow_back</span>
    Back to List
  </a>
</div>

<!-- WACC Form -->
<form name="updateform" action="<?php echo $env['self']; ?>" method="post" class="space-y-6">

  <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
      <h3 class="font-bold text-slate-900 flex items-center gap-2">
        <span class="material-symbols-outlined text-sm text-slate-500">edit</span>
        Certificate Information
      </h3>
    </div>
    <div class="p-4 space-y-4">
      <!-- CN -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">CN (Common Name) <span class="text-red-500">*</span></label>
        <div class="flex gap-2">
          <input type="text" name="cn" value="<?php echo htmlspecialchars($cn); ?>" required
            class="flex-1 px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all"
            placeholder="Enter common name">
        </div>
      </div>

      <!-- DN -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">DN (Distinguished Name)</label>
        <div class="flex gap-2">
          <input type="text" name="dn" value="<?php echo htmlspecialchars($dn); ?>"
            class="flex-1 px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all"
            placeholder="/CN=...">
          <button type="button" id="update"
            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-medium transition-all">
            Auto Fill
          </button>
        </div>
        <p class="text-xs text-slate-400 mt-1">Click "Auto Fill" to generate DN from CN</p>
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all"
          placeholder="user@example.com">
      </div>

      <!-- Serial -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Serial <span class="text-red-500">*</span></label>
        <input type="text" name="serial" value="<?php echo htmlspecialchars($serial); ?>" required
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all font-mono"
          placeholder="Certificate serial number">
      </div>

      <!-- PIN -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">PIN <span class="text-red-500">*</span></label>
        <input type="text" name="pin" value="<?php echo htmlspecialchars($pin); ?>" required
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all font-mono"
          placeholder="PIN number">
      </div>

      <!-- Subscriber ID -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Subscriber ID (SID)</label>
        <input type="text" name="subscid" value="<?php echo htmlspecialchars($subscid); ?>"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all"
          placeholder="Linked subscriber ID">
      </div>

      <!-- Role -->
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Role <span class="text-red-500">*</span></label>
        <div class="flex gap-4 flex-wrap">
          <?php
          $chk1 = $chk2 = $chk3 = '';
          if ($mode == 'edit') {
            if ($row['authz'] == 'ca') $chk1 = ' checked';
            else if ($row['authz'] == 'ra') $chk2 = ' checked';
            else if ($row['authz'] == 'user') $chk3 = ' checked';
          } else {
            $chk3 = ' checked';
          }
          ?>
          <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2 rounded-lg border border-slate-200 hover:border-red-300 transition-all">
            <input type="radio" name="authz" value="user"<?php echo $chk3; ?>
              class="w-4 h-4 text-red-500 border-slate-300 focus:ring-red-500">
            <span class="text-sm text-slate-700">Subscriber</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2 rounded-lg border border-slate-200 hover:border-amber-300 transition-all">
            <input type="radio" name="authz" value="ra"<?php echo $chk2; ?>
              class="w-4 h-4 text-amber-500 border-slate-300 focus:ring-amber-500">
            <span class="text-sm text-slate-700">RA</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer bg-slate-50 px-4 py-2 rounded-lg border border-slate-200 hover:border-red-300 transition-all">
            <input type="radio" name="authz" value="ca"<?php echo $chk1; ?>
              class="w-4 h-4 text-red-500 border-slate-300 focus:ring-red-500">
            <span class="text-sm text-slate-700">CA</span>
          </label>
        </div>
      </div>
    </div>
  </div>

  <!-- Submit Buttons -->
  <?php if ($mode == 'edit'): ?>
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  <?php endif; ?>
  <input type="hidden" name="mode" value="do<?php echo $mode; ?>">

  <div class="grid grid-cols-2 gap-3">
    <a href="<?php echo $env['self']; ?>"
       class="flex items-center justify-center gap-2 bg-slate-500 hover:bg-slate-600 text-white py-3 rounded-xl font-bold transition-all">
      <span class="material-symbols-outlined text-sm">close</span>
      Cancel
    </a>
    <button type="submit"
       class="flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-bold transition-all">
      <span class="material-symbols-outlined text-sm">save</span>
      <?php echo $mode == 'add' ? 'Add WACC' : 'Save Changes'; ?>
    </button>
  </div>
</form>

<script>
const updateBtn = document.getElementById("update");
updateBtn.addEventListener("click", update_dn);

function update_dn(){
  var form = document.updateform;
  var cn = form.cn.value;
  form.dn.value = "/CN=" + cn;
}
</script>

<?php
  include("tail.php");
  exit;


} else if ($mode == 'makecert') {

  $id = $form['id'];
  $qry = "SELECT * FROM webcert WHERE id='$id'";
  $row = DBQueryAndFetchRow($qry);

  $cn = $row['cn'];
  $serial = $row['serial'];
  $pin = $row['pin'];

  include("head.php");
?>

<!-- Page Header -->
<div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-red-500">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-red-500">terminal</span>
    <h1 class="text-xl font-bold text-slate-900">Certificate Generation Script</h1>
  </div>
  <a href="<?php echo $env['self']; ?>" class="text-slate-500 hover:text-slate-700 text-sm flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">arrow_back</span>
    Back to List
  </a>
</div>

<!-- Script Info -->
<div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
  <div class="flex items-start gap-3">
    <span class="material-symbols-outlined text-amber-600">info</span>
    <div>
      <p class="font-bold text-slate-900">Certificate Details</p>
      <p class="text-sm text-slate-600 mt-1">CN: <?php echo htmlspecialchars($cn); ?> | Serial: <?php echo htmlspecialchars($serial); ?></p>
    </div>
  </div>
</div>

<!-- Script Card -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="px-4 py-3 border-b border-slate-100 bg-slate-800 flex items-center justify-between">
    <h3 class="font-bold text-white flex items-center gap-2">
      <span class="material-symbols-outlined text-sm text-slate-400">code</span>
      Shell Script
    </h3>
    <button onclick="copyScript()" class="text-xs bg-slate-700 hover:bg-slate-600 text-white px-3 py-1 rounded transition-all">
      Copy
    </button>
  </div>
  <div class="p-4 bg-slate-900 overflow-x-auto">
    <pre id="script-content" class="text-green-400 text-sm font-mono whitespace-pre leading-relaxed">
cd /root/wacc/

rm -rf <?php echo $serial; ?>

mkdir <?php echo $serial; ?>

cd <?php echo $serial; ?>


openssl req -new -sha1 -newkey rsa:1024 -nodes \
  -keyout client.key \
  -out request.pem \
  -subj '/CN=<?php echo $cn; ?>'

openssl x509 -CA /etc/pki/tls/certs/server.crt \
 -CAkey /etc/pki/tls/certs/server.key \
 -set_serial <?php echo $serial; ?> \
 -days 1080 -extensions ssl_client  \
 -req -in request.pem  -out client.pem

echo "<?php echo $pin; ?>" > pass
openssl pkcs12 -export -clcerts -in client.pem -inkey client.key \
 -out client.p12 -passout file:pass

openssl x509 -in client.pem -text -noout

mv client.key <?php echo $serial; ?>.key
mv client.pem <?php echo $serial; ?>.crt
mv client.p12 <?php echo $serial; ?>.p12

rm -f pass
history -c
</pre>
  </div>
</div>

<!-- Back Link -->
<div class="text-center">
  <a href="<?php echo $env['self']; ?>" class="text-red-600 hover:text-red-700 text-sm font-medium inline-flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">arrow_back</span>
    Back to WACC List
  </a>
</div>

<script>
function copyScript() {
  const scriptContent = document.getElementById('script-content').innerText;
  navigator.clipboard.writeText(scriptContent).then(function() {
    alert('Script copied to clipboard!');
  });
}
</script>

<?php
  include("tail.php");
  exit;
}

####################### main ###########################################

include("head.php");

$qry = "SELECT * FROM webcert ORDER BY serial DESC, idate DESC";
$ret = DBQuery($qry);
$waccs = array();
while ($row = DBFetchRow($ret)) {
  $waccs[] = $row;
}
?>

<!-- Page Header -->
<div class="flex items-center justify-between pb-4 mb-6 border-b-2 border-red-500">
  <div class="flex items-center gap-3">
    <span class="material-symbols-outlined text-red-500">badge</span>
    <h1 class="text-xl font-bold text-slate-900">WACC List</h1>
  </div>
  <a href="<?php echo $env['self']; ?>?mode=add" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-1">
    <span class="material-symbols-outlined text-sm">add</span>
    Add WACC
  </a>
</div>

<!-- Info Notice -->
<div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
  <div class="flex items-start gap-3">
    <span class="material-symbols-outlined text-blue-600">info</span>
    <p class="text-sm text-slate-700">Web Access Client Certificates not registered here cannot access their assigned Role's web portal.</p>
  </div>
</div>

<?php if (count($waccs) == 0): ?>
<!-- Empty State -->
<div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
  <span class="material-symbols-outlined text-slate-300 text-5xl mb-3">badge</span>
  <p class="text-slate-500">No WACC entries found</p>
  <a href="<?php echo $env['self']; ?>?mode=add" class="mt-4 inline-flex items-center gap-2 text-red-600 font-medium">
    <span class="material-symbols-outlined text-sm">add</span>
    Add first WACC
  </a>
</div>
<?php else: ?>

<!-- Desktop Table -->
<div class="hidden md:block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-slate-50 border-b border-slate-200">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">ID</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">CN</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Serial</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Role</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Date</th>
          <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">PIN</th>
          <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($waccs as $row):
          $id = $row['id'];
          $cn = $row['cn'];
          $serial = $row['serial'];
          $pin = $row['pin'];
          $authz = $row['authz'];
          $idate = substr($row['idate'], 0, 10);

          // Role badge color
          $roleClass = 'bg-slate-100 text-slate-700';
          if ($authz == 'ca') $roleClass = 'bg-red-100 text-red-700';
          elseif ($authz == 'ra') $roleClass = 'bg-amber-100 text-amber-700';
          elseif ($authz == 'user') $roleClass = 'bg-blue-100 text-blue-700';
        ?>
        <tr class="hover:bg-slate-50 transition-colors">
          <td class="px-4 py-3 text-slate-500"><?php echo $id; ?></td>
          <td class="px-4 py-3">
            <a href="<?php echo $env['self']; ?>?mode=edit&id=<?php echo $id; ?>" class="text-red-600 font-medium hover:underline"><?php echo htmlspecialchars($cn); ?></a>
          </td>
          <td class="px-4 py-3 font-mono text-sm"><?php echo htmlspecialchars($serial); ?></td>
          <td class="px-4 py-3">
            <span class="text-xs px-2 py-1 rounded font-bold uppercase <?php echo $roleClass; ?>"><?php echo htmlspecialchars($authz); ?></span>
          </td>
          <td class="px-4 py-3 text-slate-500 text-sm whitespace-nowrap"><?php echo $idate; ?></td>
          <td class="px-4 py-3 font-mono text-sm"><?php echo htmlspecialchars($pin); ?></td>
          <td class="px-4 py-3">
            <div class="flex justify-center gap-2">
              <a href="<?php echo $env['self']; ?>?mode=makecert&id=<?php echo $id; ?>" target="_blank"
                 class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-1 rounded transition-all">
                Script
              </a>
              <button onclick="handleDelete(<?php echo $id; ?>)"
                 class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded transition-all">
                Delete
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3 mb-6">
  <?php foreach ($waccs as $row):
    $id = $row['id'];
    $cn = $row['cn'];
    $serial = $row['serial'];
    $pin = $row['pin'];
    $authz = $row['authz'];
    $idate = substr($row['idate'], 0, 10);

    // Role badge color
    $roleClass = 'bg-slate-100 text-slate-700';
    if ($authz == 'ca') $roleClass = 'bg-red-100 text-red-700';
    elseif ($authz == 'ra') $roleClass = 'bg-amber-100 text-amber-700';
    elseif ($authz == 'user') $roleClass = 'bg-blue-100 text-blue-700';
  ?>
  <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
    <div class="flex justify-between items-start mb-3">
      <div>
        <a href="<?php echo $env['self']; ?>?mode=edit&id=<?php echo $id; ?>" class="font-bold text-slate-900 hover:text-red-600"><?php echo htmlspecialchars($cn); ?></a>
        <p class="text-xs text-slate-500 mt-1">ID: <?php echo $id; ?> | Serial: <?php echo htmlspecialchars($serial); ?></p>
      </div>
      <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase <?php echo $roleClass; ?>"><?php echo htmlspecialchars($authz); ?></span>
    </div>
    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
      <div class="text-xs text-slate-500">
        <span class="font-mono">PIN: <?php echo htmlspecialchars($pin); ?></span>
        <span class="mx-2">|</span>
        <span><?php echo $idate; ?></span>
      </div>
      <div class="flex gap-2">
        <a href="<?php echo $env['self']; ?>?mode=makecert&id=<?php echo $id; ?>" target="_blank"
           class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-2 py-1 rounded transition-all">
          Script
        </a>
        <button onclick="handleDelete(<?php echo $id; ?>)"
           class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded transition-all">
          Del
        </button>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php endif; ?>

<script>
function handleDelete(id) {
  if (confirm('Are you sure you want to delete this WACC entry?')) {
    window.location.href = '<?php echo $env['self']; ?>?mode=del&id=' + id;
  }
}
</script>

<?php
include("tail.php");
?>
