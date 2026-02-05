<?php

include("common.php");


/*
if ($mode == 'test2') {
  print<<<EOS
<script>
//alert(window);
//alert(opener);
//alert(opener.document.location);
opener.document.location = 'about:blank';
window.close();
</script>
EOS;
  exit;
}

if ($mode == 'test') {
  print<<<EOS
<a href='$env[self]?mode=test2'>go</a>
<form action='$env[self]' method='post'>
<input type='hidden' name='mode' value='test2'>
<input type='submit'>
</form>
EOS;
  exit;
}

<a href="javascript:void(0);" onclick="window.open('$env[self]?mode=test&popup=1',
'test','width=300,height=300')">Add</a>
*/

# ?߰? / ??��
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
#   print("$qry");
    $ret = DBQuery($qry);
#   print DBError();

  } else if ($mode == 'doedit') {
    $id = $form['id'];
    $qry = "UPDATE webcert SET"
         ." cn='$cn',serial='$serial',pin='$pin'"
         .",dn='$dn'"
         .",authz='$authz'"
         .",email='$email'"
         .",udate=NOW() WHERE id='$id'";
#   print("$qry");
    $ret = DBQuery($qry);
#   print DBError();
  }

  Redirect("$env[self]?".time());
  exit;

# ??��
} else if ($mode == 'del') {
  $id = $form['id'];
  $qry = "DELETE FROM webcert WHERE id='$id'";
  $ret = DBQuery($qry);
  Redirect("$env[self]?".time());


# ?߰?/??��
} else if ($mode == 'add' or $mode == 'edit') {

  include("head.php");
  if ($mode == 'add') $title = "WACC List - Add";
  else if ($mode == 'edit') $title = "WACC List - Edit";
  ParagraphTitle($title);

  if ($mode == 'add') {
    $qry = "SELECT MAX(serial) AS max FROM webcert";
    $row = DBQueryAndFetchRow($qry);
    $serial = $row['max'] + 1;

    $cn = $form['cn'];
    $pin = $form['pin'];
    $email = $form['email'];
    $subscid = $form['subscid'];

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

  print<<<EOS
<style>
td.a { background:#cccccc; height:30; text-align:right; }
td.b { background:#eeeeee; height:30; text-align:left; }
td.c { background:#cccccc; height:30; text-align:center; }
</style>

<a href='$env[self]?mode=add'>Add</a> ::
<a href='$env[self]'>List</a>
<br><br>

<script>
function update_dn() {
  var form = document.form;
  var cn = form.cn.value;
  form.dn.value = "/CN=" + cn;
}
</script>

<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<form name='form' action='$env[self]' method='post'>
<tr>
 <td class='a'>CN:</td>
 <td class='b'><input type='text' name='cn' size='20' value='$cn'></td>
</tr>
<tr>
 <td class='a'>DN:</td>
 <td class='b'><input type='text' name='dn' size='40' value='$dn'>
  <input type='button' onclick='update_dn()' value='update'>
 </td>
</tr>
<tr>
 <td class='a'>Email:</td>
 <td class='b'><input type='text' name='email' size='30' value='$email'></td>
</tr>
<tr>
 <td class='a'>Serial:</td>
 <td class='b'><input type='text' name='serial' size='10' value='$serial'></td>
</tr>
<tr>
 <td class='a'>PIN:</td>
 <td class='b'><input type='text' name='pin' size='10' value='$pin'></td>
</tr>
<tr>
 <td class='a'>Subscriber ID(SID)</td>
 <td class='b'><input type='text' name='subscid' size='10' value='$subscid'></td>
</tr>
<tr>
 <td class='a'>Role</td>
 <td class='b'>
EOS;
  $chk1 = $chk2 = $chk3 = '';
  if ($mode == 'edit') {
    if ($row['authz'] == 'ca') $chk1 = ' checked';
    else if ($row['authz'] == 'ra') $chk2 = ' checked';
    else if ($row['authz'] == 'user') $chk3 = ' checked';
  } else if ($mode == 'add') {
    $chk3 = ' checked';
  }
  print<<<EOS
   <input type='radio' name='authz' value='user'$chk3>Subscriber
   <input type='radio' name='authz' value='ra'$chk2>RA
   <input type='radio' name='authz' value='ca'$chk1>CA
 </td>
</tr>
<tr>
 <td class='c' colspan='3'>
   <input type='submit' value=' OK '>
EOS;
  if ($mode == 'edit') {
    print<<<EOS
   <input type='hidden' name='id' value='$id'>
EOS;
  }
  print<<<EOS
   <input type='hidden' name='mode' value='do$mode'>
 </td>
</tr>
</form>
</table>
EOS;

  include("tail.php");
  exit;


} else if ($mode == 'makecert') {

  $id = $form['id'];
  $qry = "SELECT * FROM webcert WHERE id='$id'";
  $row = DBQueryAndFetchRow($qry);
  //print_r($row);
 
  $cn = $row['cn'];
  $serial = $row['serial'];
  $pin = $row['pin'];

  print<<<EOS
<pre>

cd /root/wacc/

rm -rf $serial
mkdir $serial
cd $serial

openssl req -new -sha1 -newkey rsa:1024 -nodes \
  -keyout client.key \
  -out request.pem \
  -subj '/CN=$cn'

openssl x509 -CA /www2/conf/ssl/pki.gridcenter.or.kr.crt \
 -CAkey /www2/conf/ssl/pki.gridcenter.or.kr.key \
 -set_serial $serial \
 -days 1080 -extensions ssl_client  \
 -req -in request.pem  -out client.pem

echo "$pin" > pass
openssl pkcs12 -export -clcerts -in client.pem -inkey client.key \
 -out client.p12 -passout file:pass

openssl x509 -in client.pem -text -noout

mv client.key $serial.key
mv client.pem $serial.crt
mv client.p12 $serial.p12

rm -f pass
history -c

</pre>
<br>
EOS;
  exit;
}

####################### main ###########################################

  include("head.php");
  $title = "WACC List";
  ParagraphTitle($title);

  print<<<EOS
<style>
td.x { background:#cccccc; text-align:center; padding:5px 5px 5px 5px; }
td.y { background:#eeeeee; text-align:center; padding:5px 5px 5px 5px; }
</style>
<a href='$env[self]?mode=add'>Add</a> ::
<a href='$env[self]'>List</a>
<br><br>
여기에 등록되어 있지 않은 인증서로는 Role에 해당하는 웹사이트에 접근을 할 수 없음. 
<br>
<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<tr>
 <td class='x'>ID</td>
 <td class='x'>CN</td>
 <td class='x'>Serial</td>
 <td class='x'>Role</td>
 <td class='x'>Date</td>
 <td class='x'>PIN</td>
 <td class='x'>make script</td>
 <td class='x'>del</td>
</tr>
EOS;
  $qry = "SELECT * FROM webcert ORDER BY serial DESC, idate DESC";
  $ret = DBQuery($qry);
  while ($row = DBFetchRow($ret)) {
    //print_r($row);
    $id = $row['id'];
    $cn = $row['cn'];
    $serial = $row['serial'];
    $pin = $row['pin'];
    $authz = $row['authz'];
    $subscid = $row['subscid'];

    $makecert=<<<EOS
<a href='$env[self]?mode=makecert&id=$id' target='_blank'>make script</a>
EOS;
    $del =<<<EOS
<a href="javascript:script_Question('$env[self]?mode=del&id=$id','Delete?')">del</a>
EOS;
    print<<<EOS
<tr>
 <td class='y'>$id</td>
 <td class='y'><a href='$env[self]?mode=edit&id=$id'>$cn</a></td>
 <td class='y'>$serial</td>
 <td class='y'>$authz</td>
 <td class='y'>$row[idate]</td>
 <td class='y'>$pin</td>
 <td class='y'>$makecert</td>
 <td class='y'>$del</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
EOS;

  include("tail.php");
  exit;

?>
