<?php

include("common.php");


# print_r($_SERVER);
if ($mode == 'register') {
# print_r($form);
# print_r($_SERVER);

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
  Redirect("/ra/subscribers.php");
  exit;
}


  include("head.php");

  $title = "Register A New Subscriber";
  ParagraphTitle($title);

//print_r($_SERVER);
  print<<<EOS
<br>
<style>
td.x { background:#cccccc; text-align:center; padding:2 2 2 2px; }
td.y { background:#eeeeee; text-align:left; padding:2 2 2 2px; }
</style>
<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<form action='$env[self]' name='form' method='post'>
<tr>
  <td class='x'>First Name</td>
  <td class='y'><input type='text' name='firstname' size='20'></td>
</tr>
<tr>
  <td class='x'>Last Name</td>
  <td class='y'><input type='text' name='lastname' size='20'></td>
</tr>
<tr>
  <td class='x'>Gender</td>
  <td class='y'>
    <input type='radio' name='gender' value='M'>Male
    <input type='radio' name='gender' value='F'>Female
  </td>
</tr>
<tr>
  <td class='x'>Country</td>
  <td class='y'>
    <select name='country'>
      <option value='Korea'>Korea</option>
      <option value='China'>China</option>
      <option value='Vietnam'>Vietnam</option>
      <option value='Saudi Arabia'>Saudi Arabia</option>
      <option value='Australia'>Australia</option>
    </select>
  </td>
</tr>
<script>
function select_org() {
  var sel = document.form.sel_org;
  var idx = sel.selectedIndex;
  var org = sel[idx].value;
  //alert(org);
  if (org == '_ETC_') {
    document.form.org.value = '';
    document.form.org.focus();
    return;
  }
  document.form.org.value=org; 
}
</script>
EOS;

  $opts = '';
  $qry = "SELECT org FROM subscriber GROUP BY org";
  $ret = DBQuery($qry);
  while ($row = DBFetchRow($ret)) {
    $org = $row['org'];
#   print_r($row);
    $opts .= "<option value='$org'>$org</option>";
  }
  print<<<EOS
<tr>
  <td class='x'>Organization</td>
  <td class='y'>
    <input type='text' name='org' size='20'>
    <select name='sel_org' onchange="select_org()">
      <option value=''>::SELECT::</option>
      $opts
      <option value='_ETC_'>etc.(specify)</option>
    </select>
</tr>
<tr>
  <td class='x'>Organization Unit</td>
  <td class='y'><input type='text' name='orgunit' size='40'></td>
</tr>
<tr>
  <td class='x'>Position</td>
  <td class='y'><input type='text' name='position' size='40'></td>
</tr>
<tr>
  <td class='x'>Email</td>
  <td class='y'><input type='text' name='email' size='30'></td>
</tr>
<tr>
  <td class='x' colspan='2'>
   <input type='hidden' name='mode' value='register'>
   <input type='submit' value=' submit '>
  </td>
</tr>
</form>
</table>
EOS;

  include("tail.php");
  exit;

?>
