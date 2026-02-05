<?php

  include("common.php");

if ($mode == 'getpin') {
# print_r($form);
  $id = $form['id'];
  $pin = rand(10000000,90000000);
# print("$pin");
  $qry = "UPDATE subscriber SET pin='$pin' WHERE id='$id'";
# print("$qry");
  $ret = DBQuery($qry);
  Redirect("$env[self]?mode=info&id=$id");
  exit;

} else if ($mode == 'info') {
  $id = $form['id'];

  include("head.php");
  ParagraphTitle('Subscriber Information');

  $qry = "SELECT * FROM subscriber WHERE id='$id'";
  $row = DBQueryAndFetchRow($qry);
# print_r($row);
  $email = $row['email'];

  print<<<EOS
<br>
<style>
td.a { background:#cccccc; text-align:center; padding:5 5 5 5px; }
td.b { background:#eeeeee; text-align:left;   padding:5 5 5 5px; }
</style>

<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<tr>
 <td class='a'>First name</td>
 <td class='b'>{$row['firstname']}</td>
</tr>
<tr>
 <td class='a'>Last name</td>
 <td class='b'>{$row['lastname']}</td>
</tr>
EOS;
  if ($row['perid'] == '') {
    $perid =<<<EOS
Not yet assigned by CA.
EOS;
  } else {
    $perid =<<<EOS
{$row['perid']}
EOS;
  }
  print<<<EOS
<tr>
 <td class='a'>Personal No.</td>
 <td class='b'>$perid</td>
</tr>
<tr>
 <td class='a'>Country</td>
 <td class='b'>{$row['country']}</td>
</tr>
<tr>
 <td class='a'>Organization</td>
 <td class='b'>{$row['org']}</td>
</tr>
<tr>
 <td class='a'>Organization Unit</td>
 <td class='b'>{$row['orgunit']}</td>
</tr>
<tr>
 <td class='a'>Position</td>
 <td class='b'>{$row['position']}</td>
</tr>
<tr>
 <td class='a'>Email</td>
 <td class='b'>{$email}</td>
</tr>
<tr>
 <td class='a'>Registration Date</td>
 <td class='b'>{$row['idate']}</td>
</tr>
<tr>
 <td class='a'>Charging RA</td>
 <td class='b'>{$row['ra_cn']}</td>
</tr>
EOS;
  if ($row['pin'] == '') {
    $pin=<<<EOS
<a href='$env[self]?mode=getpin&id=$id'>[[Get it now]]</a>
EOS;
  } else {
    $pin=<<<EOS
{$row['pin']}
EOS;
  }
  print<<<EOS
<tr>
 <td class='a'>PIN#</td>
 <td class='b'>{$pin}</td>
</tr>
EOS;
  if ($env['role'] == 'CA') {
    $subscid = $row['id'];
    print<<<EOS
<tr>
 <td class='a'>Register WACC</td>
 <td class='b'><a href='wacc.php?mode=add&pin=$pin&email=$email&cn=$email&subscid=$subscid'>[[Register WACC List]]</a></td>
</tr>
EOS;
  }
  print<<<EOS
</table>
EOS;
   include("tail.php");
  exit;
}


  include("head.php");

  $title = "Subscribers";
  ParagraphTitle($title);

//print_r($_SERVER);
  print<<<EOS
<br>
<style>
td.a { background:#cccccc; text-align:center; padding:2 2 2 2px; }
td.b { background:#eeeeee; text-align:left; padding:2 2 2 2px; }
</style>

<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<tr>
<td class='a'>ID</td>
<td class='a'>First name</td>
<td class='a'>Last name</td>
<td class='a'>Organization</td>
<td class='a'>Org. unit</td>
<td class='a'>Position</td>
<td class='a'>Email</td>
<td class='a'>Reg. Date</td>
<td class='a'>Personal No.</td>
</tr>
EOS;
  $qry = "SELECT * FROM subscriber ORDER BY idate DESC";
  $ret = DBQuery($qry);
  while ($row = DBFetchRow($ret)) {
#   print_r($row);
    $id = $row['id'];
    $idate  = substr($row['idate'],0,10);
    print<<<EOS
<tr>
<td class='b'><a href='$env[self]?mode=info&id=$id'>$id</a></td>
<td class='b'><a href='$env[self]?mode=info&id=$id'>{$row['firstname']}</a></td>
<td class='b'>{$row['lastname']}</td>
<td class='b'>{$row['org']}</td>
<td class='b'>{$row['orgunit']}</td>
<td class='b'>{$row['position']}</td>
<td class='b'>{$row['email']}</td>
<td class='b'>{$idate}</td>
<td class='b'>{$row['perid']}</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
EOS;

  include("tail.php");
  exit;

?>
