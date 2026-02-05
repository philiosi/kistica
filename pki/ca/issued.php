<?php

  include("common.php");

  $env['pagewidth'] = 1000;

  include("head.php");
  $title = "Issued Certificates List";
  ParagraphTitle($title);

  $sql_where = ' WHERE 1';
  $search = $form['search'];
  if ($search) {
    $sql_where .= " AND ((subject LIKE '%$search%'))";
  }
  $sql_where .= " AND status='issued'";


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
<td>Search:</td>
<td><input type='text' name='search' size='20' value='$search'></td>
</tr>
</form>
</table>
EOS;

  print<<<EOS
<style>
td.a { text-align:center; background:#cccccc; padding:3 9 3 9px; }
td.b { text-align:left;   background:#ffffff; padding:3 9 3 9px; }
</style>

<a href='http://ca.gridcenter.or.kr/issued/'>Public issued certificate list</a><br>

<table border='0' cellpadding='1' cellspacing='1' width='100%'>
<tr>
 <td class='a'>File</td>
 <td class='a'>Subject</td>
 <td class='a'>Type</td>
 <td class='a' nowrap>Valid Until</td>
</tr>
EOS;

  $prefix = "http://ca.gridcenter.or.kr/issued/";

  $qry = "SELECT * FROM cert $sql_where ORDER BY serial DESC";
  $qry .= " LIMIT $start,$ipp";

  $ret = DBQuery($qry);

  while ($row = DBFetchRow($ret)) {
#   print_r($row);
    $serial = $row['serial'];
    $serial_h = sprintf("%02x", $serial);

    $subject = $row['subject'];
    $ctype = $row['ctype'];
    if ($ctype == 'user') $ctype_s = 'person';
    else $ctype_s = 'host';
    $vuntil = $row['vuntil'];
    $vuntil_s = substr($vuntil, 0, 10);
    print<<<EOS
<tr>
 <td class='b'><a href='$prefix$serial_h.pem'>$serial_h.pem</a></td>
 <td class='b'><a href='$prefix$serial_h.txt'>$subject</a></td>
 <td class='b'>$ctype_s</td>
 <td class='b' nowrap>$vuntil_s</td>
</tr>
EOS;
  }

  print<<<EOS
</table>
EOS;

  print<<<EOS
<p style="line-height:160%;">
</p>
EOS;


  include("tail.php");

?>
