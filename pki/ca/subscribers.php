<?php

  include("common.php");


##############################################################
### modes {{{
##############################################################

if ($mode == 'mail1') {

  $id = $form['id'];
  $qry = "SELECT * FROM subscriber WHERE id='$id'"; 
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
# print_r($row);

  include("head.php");

# print_r($row);
  print<<<EOS
$row[firstname]
$row[lastname]
EOS;
  include("tail.php");
  exit;


// personal no 얻기
} else if ($mode == 'getperid') {
# print_r($form);

  $id = $form['id'];
  $qry = "SELECT * FROM subscriber WHERE id='$id'"; 
  $row = DBQueryAndFetchRow($qry);
# print_r($row);

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


// 수정 저장
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
  //print_r($form);
  $ret = mysql_query($qry);
  print mysql_error();

  Redirect("$env[self]?mode=info&id=$id");
  exit;

// 정보보기 / 수정
} else if ($mode == 'info' or $mode == 'edit') {

  $id = $form['id'];

  include("head.php");
  ParagraphTitle('Subscriber Information');

  $qry = "SELECT * FROM subscriber WHERE id='$id'";
  $row = DBQueryAndFetchRow($qry);
# print_r($row);
  $email = $row['email'];

  $fn = $row['firstname'];
  $ln = $row['lastname'];

  if ($mode == 'info') {
    print<<<EOS
<a href='$env[self]?mode=edit&id=$id'>Modify</a><br>
EOS;
  }

  print<<<EOS
<style>
td.a { background:#cccccc; text-align:center; padding:5 5 5 5px; }
td.b { background:#eeeeee; text-align:left;   padding:5 5 5 5px; }
</style>

<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<form name='form2' method='post' action='$env[self]'>
EOS;

  if ($row['perid'] == '') {
    $perid =<<<EOS
<a href='$env[self]?mode=getperid&id=$id'>[[Assign it now]]</a>
EOS;
  } else {
    $perid =<<<EOS
{$row['perid']}
EOS;
  }

  print<<<EOS
<tr>
 <td class='a'>ID</td>
 <td class='b'>{$row['id']}</td>
</tr>
EOS;

  print<<<EOS
<tr>
 <td class='a'>Personal No.</td>
 <td class='b'>$perid</td>
</tr>
EOS;

  print<<<EOS
<tr>
 <td class='a'>Name</td>
 <td class='b'>$fn $ln</td>
</tr>
EOS;

  if ($mode == 'info') {
    print<<<EOS
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
EOS;
  } else if ($mode == 'edit') {
    print<<<EOS
<tr>
 <td class='a'>Country</td>
 <td class='b'><input type='text' name='country' size='40' value="{$row['country']}"></td>
</tr>
<tr>
 <td class='a'>Organization</td>
 <td class='b'><input type='text' name='org' size='40' value="{$row['org']}"></td>
</tr>
<tr>
 <td class='a'>Organization Unit</td>
 <td class='b'><input type='text' name='orgunit' size='40' value="{$row['orgunit']}"></td>
</tr>
<tr>
 <td class='a'>Position</td>
 <td class='b'><input type='text' name='position' size='40' value="{$row['position']}"></td>
</tr>
<tr>
 <td class='a'>Email</td>
 <td class='b'><input type='text' name='email' size='40' value="{$email}"></td>
</tr>
EOS;
  } 

  print<<<EOS
/tr>
 <td class='a'>Registration Date</td>
 <td class='b'>{$row['idate']}</td>
</tr>
<tr>
 <td class='a'>Charging RA</td>
 <td class='b'>{$row['ra_cn']}</td>
</tr>
EOS;

  if ($mode == 'edit') {

  print<<<EOS
<tr>
 <td class='b' colspan='2'>
<input type='hidden' name='id' value='$id'>
<input type='hidden' name='mode' value='doedit'>
<input type='submit' value='Save'>
</td>
</form>
EOS;
    include("tail.php");
    exit;
  }


  if ($row['pin'] == '') {
    $pin=<<<EOS
not yet assigned
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


  $subscid = $row['id'];
  print<<<EOS
<tr>
 <td class='a'>Register WACC</td>
 <td class='b'><a href='wacc.php?mode=add&pin=$pin&email=$email&cn=$email&subscid=$subscid'>[[Register WACC List]]</a></td>
</tr>
EOS;

  print<<<EOS
<tr>
 <td class='a'>Delete</td>
 <td class='b'><a href="javascript:script_Question('$env[self]?mode=del&id=$id','Delete?')">[[delete]]</a></td>
</tr>
EOS;


  print<<<EOS
<form name='form' action='$env[self]' method='post'>
<tr>
 <td class='a'>Memo</td>
 <td class='b'><input type='text' name='memo' value='$row[memo]' size='20'>
<input type='submit' value='save'>
<input type='hidden' name='id' value='$id'>
<input type='hidden' name='mode' value='savememo'>
 </td>
</tr>
</form>
EOS;

  print<<<EOS
</table>
EOS;


  ParagraphTitle("Subscriber's certs");

  print<<<EOS
<style>
table.x { border-collapse:collapse; }
table.x th { border:1px solid #666666; background-color:#ccc; padding:5px 5px 5px 5px; }
table.x td { border:1px solid #666666; background-color:#eee; padding:5px 5px 5px 5px; }
</style>

<table class='x'>
<tr>
 <th>Serial</th>
 <th>Subject</th>
 <th>From</th>
 <th>Until</th>
 <th>Status</th>
</tr>
EOS;

  $qry = "SELECT * FROM cert"
     ." LEFT JOIN csr ON cert.csrid=csr.csrid"
     ." WHERE csr.email='$email'"
     ." ORDER BY cert.idate DESC";
  //print $qry;
  $ret = DBQuery($qry);
  //print mysql_error();
  while ($row = DBFetchRow($ret)) {
    print<<<EOS
<tr>
 <td>$row[serial]</td>
 <td>$row[subject]</td>
 <td>$row[vfrom]</td>
 <td>$row[vuntil]</td>
 <td>$row[status]</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
EOS;

  include("tail.php");
  exit;


// 메모저장
} else if ($mode == 'savememo') {
  //print_r($form);
  $id = $form['id'];
  $memo = $form['memo'];

  $qry = "UPDATE subscriber SET memo='$memo' WHERE id='$id'";
  $ret = DBQuery($qry);

  Redirect("$env[self]?mode=info&id=$id");
  exit;
}


### modes }}}


  include("head.php");

  $title = "Subscribers";
  ParagraphTitle($title);

//print_r($_SERVER);

  $sql_where = '';
  $search = $form['search'];
  if ($search) {
    $sql_where = "WHERE ((s.firstname LIKE '%$search%') OR (s.lastname LIKE '%$search%')"
      ." OR (s.org LIKE '%$search%') OR (s.email LIKE '%$search%')"
      ." OR (s.memo LIKE '%$search%')"
      .")";
  }

  $qry = "SELECT count(*) as count FROM subscriber s $sql_where";
# print($qry);
  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);
  $total = $row['count'];

  $ipp = 20;

  $page = $form['page'];
  if ($page == '') $page = 1;
  $last = ceil($total/$ipp);
  if ($last == 0) $last = 1;
  if ($page > $last) $page = $last;
  $start = ($page-1) * $ipp;

  unset($form['page']);
  $qs = Qstr($form);
  $url = "$env[self]$qs";
  $pager = Pager_s($url, $page, $total, $ipp);

  $search = $form['search'];
  print<<<EOS
<table border='0'>
<form name='form' action='$env[self]' method='get'>
<tr>
<td>$pager</td>
<td>&nbsp;&nbsp;&nbsp;</td>
<td>Search:</td>
<td><input type='text' name='search' size='20' value='$search'><input
 type='submit' value='search'></td>
</tr>
</form>
</table>
EOS;

  if ($search) {
    print<<<EOS
<p style='margin:0 0 0 0;'>
검색 결과: first name, last name, org, email 에 대한 like 검색
</p>
EOS;
  }


  print<<<EOS
<style>
td.a { background:#cccccc; text-align:center; padding:4 4 4 4px; }
td.b { background:#eeeeee; text-align:left;   padding:4 4 4 4px; }
</style>

<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<tr>
<td class='a'>ID</td>
<td class='a'>Serial</td>
<td class='a'>Name</td>
<td class='a'>Organization</td>
<td class='a'>Email</td>
<td class='a'>Registration Date</td>
<td class='a'>Mail1</td>
<td class='a'>Memo</td>
</tr>
EOS;

  $qry = "SELECT s.*,w.serial FROM subscriber s"
    ." LEFT JOIN webcert w ON s.id=w.subscid"
      ." $sql_where ORDER BY s.idate DESC";
  $qry .= " LIMIT $start,$ipp";

#print $qry;
  $ret = mysql_query($qry);
#$n = mysql_affected_rows();
 #print $n;

  while ($row = mysql_fetch_array($ret)) {
#   print_r($row);
    $id = $row['id'];
    $idate  = substr($row['idate'],0,10);

    $email = $row['email'];
    $fn = $row['firstname'];
    $ln = $row['lastname'];
    $serial = $row['serial'];

    print<<<EOS
<tr>
<td class='b'><a href='$env[self]?mode=info&id=$id'>$id</a></td>
<td class='b'>$serial</td>
<td class='b'>$fn $ln</td>
<td class='b'>{$row['org']}</td>
<td class='b'><a href='mailto:$email'>$email</a></td>
<td class='b' nowrap>{$idate}</td>
<td class='b'><a href='$env[self]?mode=mail1&id=$id' target=_blank>mail</a></td>
<td class='b'>{$row['memo']}</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
EOS;

  include("tail.php");
  exit;

?>
