<?php

include('common.php');

if ($mode == 'change_type') {
  $certid = $form['certid'];
  $type = $form['type'];

  if ($type == 'user') {
    $qry_set = "ctype='user'";
  } else if ($type == 'host') {
    $qry_set = "ctype='host'";
  } else iError('error');

  $qry = "UPDATE cert SET $qry_set WHERE certid='$certid'";
  $ret = DBQuery($qry);
# print DBError();

  Redirect("$env[self]?mode=view&certid=$certid");

  exit;

} else if ($mode == 'view') {

  $certid = $form['certid'];
  //print_r($form);

  $qry = "SELECT * FROM cert WHERE certid='$certid'";
  $row = DBQueryAndFetchRow($qry);
# print_r($row);
  $cert_text = $row['cert'];


  $tmp = '/tmp';
  $file = "$tmp/cert.tmp";
  $fp = fopen($file, 'w');
# fputs($fp, "-----BEGIN CERTIFICATE REQUEST-----\n");
  fputs($fp, $cert_text);
# fputs($fp, "-----END CERTIFICATE REQUEST-----\n");
  fclose($fp);


  $cmd = "/usr/local/bin/openssl x509 -noout -text -in $file";
# print("$cmd");
  unset($out);
  $ret = exec($cmd, $out, $retval);
# print_r($out);
  $cert_view = join("\n", $out);


  include("head.php");
  ParagraphTitle('Certificate Informaiton');

  print<<<EOS
<style>
td.a { background:#cccccc; height:30; text-align:center; }
td.b { background:#eeeeee; height:30; text-align:left; }
td.c { background:#cccccc; height:30; text-align:center; }
textarea { font-family:fixedsys; }
pre { font-family:fixedsys; }
</style>
<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<form name='form' method='post' action='$env[csr]'>
<tr>
 <td class='a'>Subject</td>
 <td class='b'>{$row['subject']}</td>
</tr>
EOS;

  $ctype_s = $row['ctype'];
  $ctype_s .=<<<EOS
&nbsp;&nbsp;
[[change to
<a href='$env[self]?mode=change_type&certid=$certid&type=host'>host</a>
<a href='$env[self]?mode=change_type&certid=$certid&type=user'>user</a>
]]
EOS;
  print<<<EOS
<tr>
 <td class='a'>Type</td>
 <td class='b'>$ctype_s</td>
</tr>
<tr>
 <td class='a'>Serial</td>
 <td class='b'>{$row['serial']}</td>
</tr>
<tr>
 <td class='a'>CERT.ID</td>
 <td class='b'>{$row['certid']}</td>
</tr>
EOS;

  $status = $row['status'];
  if ($status == 'revoked') {
    $status_s = "<font color='red'>$status</font>";
  } else if ($status == 'expired') {
    $status_s = "<font color='blue'>$status</font>";
  } else {
    $status_s = $status;
  }
  print<<<EOS
<tr>
 <td class='a'>Status</td>
 <td class='b'>$status_s</td>
</tr>
EOS;
  $csrid = $row['csrid'];
  print<<<EOS
<tr>
 <td class='a'>CSR.ID</td>
 <td class='b'>$csrid <a href='csr.php?mode=view&csrid=$csrid'>[view]</td>
</tr>
<tr>
 <td class='a'>Valid From</td>
 <td class='b'>{$row['vfrom']}</td>
</tr>
<tr>
 <td class='a'>Valid Until</td>
 <td class='b'>{$row['vuntil']}</td>
</tr>
<tr>
 <td class='a'>CERT</td>
 <td class='b'>
  <span onclick="document.getElementById('cert_text').style.display='block'" style='cursor:hand;'>[view]</a>
  <div style='display:none;' id='cert_text'>
  <pre>$cert_text</pre>
  </div>
 </td>
</tr>
<tr>
 <td class='a'>CERT View</td>
 <td class='b'>
  <pre>$cert_view</pre>
</tr>
<tr>
 <td class='a'>Date</td>
 <td class='b'>$row[idate]</td>
</tr>
<tr>
 <td class='a'>Delete</td>
 <td class='b'>
   <a href="javascript:script_Question('$env[self]?mode=del&certid=$certid','delete?')">Delete this certificate</a>
 </td>
</tr>

<tr>
 <td class='a'>Revoke</td>
 <td class='b'>
   <a href="javascript:script_Question('$env[self]?mode=revoke&certid=$certid','revoke?')">Revoke this certificate</a>
 </td>
</tr>


</form>
</table>
EOS;

  include("tail.php");
  exit;

/*
} else if ($mode == 'upload') {
# print_r($form);
  $path = $form['path'];

  $cmd = "/usr/local/bin/openssl x509 -noout -in $path -subject";
# print("$cmd<br>");
  unset($out);
  $ret = exec($cmd, $out, $retval);
# print_r($out);
  $line = $out[0];
  list($k, $v) = explode("=", $line, 2);
  $subject = trim($v);

  $cmd = "/usr/local/bin/openssl x509 -noout -in $path -serial";
  unset($out);
  $ret = exec($cmd, $out, $retval);
# print_r($out);
  $line = $out[0];
  list($k, $v) = explode("=", $line, 2);
  $serial = trim($v);

  $cmd = "/usr/local/bin/openssl x509 -noout -in $path -dates";
  unset($out);
  $ret = exec($cmd, $out, $retval);
# print_r($out);
  for ($i = 0; $i < count($out); $i++) {
    $line = $out[$i];
    list($k, $v) = explode("=", $line, 2);
    if ($k == 'notBefore') $notbefore = $v;
    else if ($k == 'notAfter') $notafter = $v;
  }

#  print<<<EOS
#$subject<br>
#$serial<br>
#$notbefore<br>
#$notafter<br>
#EOS;

  $qry = "INSERT INTO cert SET serial='$serial',subject='$subject'"
        .",notbefore='$notbefore',notafter='$notafter',ctype='user',idate=NOW()";
  $ret = DBQuery($qry);
  Redirect("csr.php");
  exit;
*/

} else if ($mode == 'revoke') {
  $certid = $form['certid'];
# print_r($form);

  # �������� ������
  $qry = "SELECT * FROM cert WHERE certid='$certid'";
  $row = DBQueryAndFetchRow($qry);
  if (!$row) iError("CERT $certid not found");
# print_r($row);

  # CSR ID
  $csrid = $row['csrid'];

  $qry = "UPDATE csr SET status='revoked' WHERE csrid='$csrid'";
# print("$qry<br>");
  $ret = DBQuery($qry);
  print DBError();

  $qry = "UPDATE cert SET status='revoked' WHERE certid='$certid'";
# print("$qry<br>");
  $ret = DBQuery($qry);
  print DBError();

  Redirect("$env[self]");
  exit;

} else if ($mode == 'del') {
  $certid = $form['certid'];
# print_r($form);

  $qry = "SELECT * FROM cert WHERE certid='$certid'";
  $row = DBQueryAndFetchRow($qry);
  if (!$row) iError("CERT $certid not found");
# print_r($row);

  $csrid = $row['csrid'];

  $qry = "UPDATE csr SET status='delcert',certid=-1 WHERE csrid='$csrid'";
# print("$qry<br>");
  $ret = DBQuery($qry);
  print DBError();

  $qry = "DELETE FROM cert WHERE certid='$certid'";
# print("$qry<br>");
  $ret = DBQuery($qry);
  print DBError();

  Redirect("$env[self]");
  exit;



} else if ($mode == 'export') {


  $qry = "SELECT * FROM cert ORDER BY idate DESC";
  $ret = DBQuery($qry);

  while ($row = DBFetchRow($ret)) {
    //print_r($row);

    $certid = $row['certid'];
    $csrid = $row['csrid'];
    $subject = $row['subject'];

    $serial = $row['serial'];
    $serial_hex = sprintf("%02x", $row['serial']);
    $notbefore = $row['vfrom'];
    $notafter = substr($row['vuntil'], 0, 10);
    $idate = substr($row['idate'], 0, 10);

    $status = $row['status'];
    if ($status == 'revoked') {
      $status_s = "<font color='red'>$status</font>";
    } else if ($status == 'expired') {
      $status_s = "<font color='blue'>$status</font>";
    } else {
      $status_s = $status;
    }

    print("$certid\t");
    print("$serial\t");
    print("$serial_hex\t");
    print("$csrid\t");
    print("$subject\t");
    print("$ctype\t");
    print("$status\t");
    print("$notafter\n");
  }

  exit;
}



///////////////////////////////////////////////////////////////////

  $env['pagewidth'] = 1000;
  include("head.php");

  $title = "Issued Certificates";
  ParagraphTitle($title);

  $sql_where = 'WHERE 1';
  $search = $form['search'];
  if ($search) {
    $sql_where .= " AND ((subject LIKE '%$search%'))";
  }

  $qry = "SELECT count(*) as count FROM cert $sql_where";
# print($qry);
  $row = DBQueryAndFetchRow($qry);
  print DBError();
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
<td>Total $total certificates $page / $last pages</td>
<td>&nbsp;&nbsp;&nbsp;</td>
<td>Search:</td>
<td><input type='text' name='search' size='20' value='$search'></td>
</tr>
</form>
</table>
EOS;

  print<<<EOS
<style>
td.a { background:#cccccc; text-align:center; margin:3 3 3 3px; }
td.b { background:#eeeeee; text-align:center; margin:3 3 3 3px; white-space:nowrap; }
td.bl{ background:#eeeeee; text-align:left; margin:3 3 3 3px; white-space:nowrap; }
</style>

<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<tr>
 <td class='a'>CERT.ID</td>
 <td class='a'>Serial</td>
 <td class='a'>Serial<br/>(hex)</td>
 <td class='a'>CSR.ID</td>
 <td class='a'>Subject</td>
 <td class='a'>Type</td>
 <td class='a'>Status</td>
 <td class='a'>Valid From</td>
 <td class='a'>Valid Until</td>
</tr>
EOS;

  $qry = "SELECT * FROM cert $sql_where ORDER BY idate DESC";
  $qry .= " LIMIT $start,$ipp";
  $ret = DBQuery($qry);

  while ($row = DBFetchRow($ret)) {
    //print_r($row);

    $certid = $row['certid'];
    $csrid = $row['csrid'];
    $subject = $row['subject'];
    $subject = preg_replace("/\/C=KR\/O=KISTI\/O=GRID\//", ".../", $subject);

    $serial = $row['serial'];
    $serial_hex = sprintf("%02x", $row['serial']);

    $notbefore = substr($row['vfrom'], 0, 10);
    $notafter = substr($row['vuntil'], 0, 10);

    $idate = substr($row['idate'], 0, 10);

    $status = $row['status'];
    if ($status == 'revoked') {
      $status_s = "<font color='red'>$status</font>";
    } else if ($status == 'expired') {
      $status_s = "<font color='blue'>$status</font>";
    } else {
      $status_s = $status;
    }

    print<<<EOS
<tr>
 <td class='b'>$certid</td>
 <td class='b'>$serial</td>
 <td class='b'>$serial_hex</td>
 <td class='b'><a href='csr.php?mode=view&csrid=$csrid'>CSR $csrid</a></td>
 <td class='bl'><a href='$env[self]?mode=view&certid=$certid'>$subject</a></td>
 <td class='b'>$row[ctype]</td>
 <td class='b'>$status_s</td>
 <td class='b'>$notbefore</td>
 <td class='b'>$notafter</td>
</tr>
EOS;
  }
  print<<<EOS
</table>
<a href='$env[self]?mode=export'>export hole table</a>
EOS;

  include("tail.php");
  exit;

?>
