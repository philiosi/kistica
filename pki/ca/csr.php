<?php

# unset($env);
# $env['prefix'] = '/www2/kistica';
# include("$env[prefix]/include/common.php");
# $env['self'] = $_SERVER['SCRIPT_NAME'];

  include("common.php");

### {{{ functions

function OpenSSLGetSubjectString($dn_obj) {
  $dn = "";
  while (list($dir, $val) = each($dn_obj)) {
    if (is_array($val)) {
      for ($i = 0; $i < count($val); $i++) {
        $e = $val[$i];
        $dn .= "/$dir=$e";
      }
    } else {
      $dn .= "/$dir=$val";
    }
  }
  return $dn;
}

### }}}


if ($mode == 'view') {

  include("head.php");
  ParagraphTitle('CSR Information');

  $csrid = $form['csrid'];
  //print_r($form);

  $qry = "SELECT * FROM csr WHERE csrid='$csrid'";
  $row = DBQueryAndFetchRow($qry);
# for ($i = 0; $i < count($row); $i++) { unset($row[$i]); }
# print_r($row);

  $csr = trim($row['csr']);
  $csr_block = $csr;
  $csr_block = preg_replace('/[-]+BEGIN CERTIFICATE REQUEST[-]+/', "", $csr_block);
  $csr_block = preg_replace('/[-]+END CERTIFICATE REQUEST[-]+/', "", $csr_block);
  if (!preg_match("/BEGIN/", $csr_block)) {
    $csr_block =<<<EOS
-----BEGIN CERTIFICATE REQUEST-----
$csr_block
-----END CERTIFICATE REQUEST-----

EOS;
  }

  $csr_block = preg_replace("/\r/","",$csr_block); # ^M ���ڸ� �����.
  $csr_block = preg_replace("/\n\n/","\n",$csr_block); # �� ���� �����Ѵ�.
/*
  if ($row['csrtype'] == 'user') {
    $csr_block=<<<EOS
-----BEGIN CERTIFICATE REQUEST-----
$csr
-----END CERTIFICATE REQUEST-----
EOS;
  } else if ($row['csrtype'] == 'host') {
    $csr_block=$csr;
  }
*/

  $tmp = '/tmp';
  $file = "$tmp/cert.csr";
  $fp = fopen($file, 'w');
  fputs($fp, $csr_block);
  fclose($fp);

  # view CSR
  $cmd = "/usr/bin/openssl req -noout -text -in $file";
# print("$cmd");
  unset($out);
  $ret = exec($cmd, $out, $retval);
# print_r($out);
  $csr_view = join("\n", $out);

  # verify CSR
# http://www.phildev.net/ssl/managing_keys_csrs_crts.html
  $cmd = "/usr/bin/openssl req -verify -noout -in $file 2>&1";
# print("$cmd");
  unset($out);
  $ret = exec($cmd, $out, $retval);
# print_r($out);
  $verify_result = join("\n", $out);


# # get serial number
# $qry2 = "SELECT MAX(serial) AS max FROM cert";
# $row2 = DBQueryAndFetchRow($qry2);
# $serial = $row2['max'] + 1;


  $forminfo = $row['forminfo'];
  $fis = explode('|', $forminfo);
  $forminfo_s = '';
  $dnc = array();
  for ($i = 0; $i < count($fis); $i++) {
    $fi = $fis[$i];
    list($k,$v) = explode('=', $fi, 2);
    $dnc[$k] = $v;
    $forminfo_s .= "$k=$v<br>";
  }

  if ($row['csrtype'] == 'user') $conf_file = 'sign.user.conf';
  else if ($row['csrtype'] == 'host') $conf_file = 'sign.host.conf.tmp';
  else $conf_file = 'error_unknown_csrtype';

  if ($row['csrtype'] == 'host') {
    $fqdn = $dnc['CN'];
    $sed_cmd =<<<EOS
sed -e "s/____FQDN____/$fqdn/" sign.host.conf  > sign.host.conf.tmp

EOS;
  }

  $qry2 = "SELECT MAX(serial) AS max FROM cert";
  $row2 = DBQueryAndFetchRow($qry2);
  $max = $row2['max']+1;
  $serial = dechex($max);
 
  print<<<EOS

<style>

td.a { background:#cccccc; height:30; text-align:center; }
td.b { background:#eeeeee; height:30; text-align:left; }
td.c { background:#cccccc; height:30; text-align:center; }

textarea.console { font-family:fixedsys, consolas, monospace; }

pre.console { font-family:fixedsys, consolas, monospace; }

</style>

<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<form name='form' method='post' action='$env[self]'>
<tr>
 <td class='a'>CSR ID</td>
 <td class='b'>$row[csrid]</td>
</tr>
EOS;
  if ($row['certid'] != -1) {
    $certid = $row['certid'];
    print<<<EOS
<tr>
 <td class='a'>CERT</td>
 <td class='b'>$certid <a href='cert.php?mode=view&certid=$certid'>[view]</a></td>
</tr>
EOS;
  }


  $status = $row['status'];
  if ($status == 'revoked') {
    $status_s = "<font color='red'>$status</font>";
  } else {
    $status_s = $status;
  }

  print<<<EOS
<tr>
 <td class='a'>Upload Time</td>
 <td class='b'>$row[idate]</td>
</tr>
<tr>
 <td class='a'>Status</td>
 <td class='b'>$status_s</td>
</tr>
<tr>
 <td class='a'>Type</td>
 <td class='b'>$row[csrtype]</td>
</tr>
<tr>
 <td class='a'>Subject Infomation</td>
 <td class='b'>$forminfo_s</td>
</tr>
<tr>
 <td class='a'>Email</td>
 <td class='b'>$row[email]</td>
</tr>
EOS;
  print<<<EOS
<tr>
 <td class='a'>CSR</td>
 <td class='b'>
  <pre class=console>$csr</pre>
 </td>
</tr>
<tr>
 <td class='a'>CSR View</td>
 <td class='b'>
 <pre class=console>$csr_view</pre>
 </td>
</tr>
<tr>
 <td class='a'>Verify Result</td>
 <td class='b'>
  <pre class=console>$verify_result</pre>
 </td>
</tr>
<tr>
 <td class='a'>Script</td>
 <td class='b'>

<textarea class=console rows=50 cols=10 style="width:100%; height:300px;">
export dir=$serial

cd /kistica/ca/
mkdir \$dir
echo "$csr_block" > \$dir/csr.pem

$sed_cmd

openssl ca -config $conf_file -out \$dir/cert.pem -infiles \$dir/csr.pem</textarea>

<textarea class=console rows=50 cols=10 style="width:100%; height:100px;">
mv \$dir/cert.pem \$dir/$serial.pem

sz \$dir/$serial.pem</textarea>

<textarea class=console rows=50 cols=10 style="width:100%; height:100px;">
openssl x509 -in \$dir/cert.pem -text -noout > \$dir/cert.txt
more \$dir/cert.txt</textarea>

</pre>

 </td>
</tr>
</form>
EOS;

  // �������� ���� ���ε� ���� ����.
  // ���ε� ���� ����Ͽ� �ش�.
  if ($row['certid'] <= 0) {
    print<<<EOS
<tr>
 <td class='a'>Upload Cert</td>
 <td class='b'>

<table border='1'>
<form name='form_cert_upload' method='post' action='$env[self]' enctype='multipart/form-data'>
<tr>
<td>
  Certificate type:
  <input type='radio' name='ctype' value='user'> person
  <input type='radio' name='ctype' value='host' checked> host
</td>
</tr>
<tr>
<td>
  <input type='file' name='file1' size='50'><br>
  <input type='hidden' name='csrid' value='$csrid'>
</td>
</tr>
<tr>
<td>
  <input type='button' value=' Upload certificate file ' onclick="_submit()">
  <input type='hidden' name='mode' value='upload'>
</td>
</tr>
</form>
</table>

<script>
function _submit() {
  var form = document.form_cert_upload;
  var regexp = /$serial.pem/;
  var str = form.file1.value;
  if (str.search(regexp) == -1) {
    alert("Check the selected file. File name should be '$serial.pem'");
    return;
  }
  form.submit();
}
</script>

 </td>
</tr>
EOS;
  }
  print<<<EOS
</table>
EOS;

  include("tail.php");
  exit;
}


###################################################################
# ������ ���ε�
###################################################################
else if ($mode == 'upload') {

# print_r($form);
  $postfile = $_FILES['file1'];
# print_r($postfile);

  $tmpname = $postfile['tmp_name'];

  if ($tmpname == '') iError('upload error');
  $name = $postfile['name'];
  $target = "/tmp/$name";
  $ret = copy($tmpname, $target); # copy file
  $path = $target;

  $cert_text = file_get_contents($path);
  $csrid = $form['csrid'];

/* dn_obj �� ��
Array (
    [C] => KR
    [O] => Array (
            [0] => KISTI
            [1] => GRID
            [2] => KISTI Grid Team
        )
    [CN] => Sangwan Kim
)
*/

# http://kr.php.net/manual/kr/function.openssl-x509-parse.php
# openssl_x509_parse()
# (PHP 4 >= 4.0.6, PHP 5)
# The structure of the returned data is (deliberately) not yet documented,
# as it is still subject to change.

  $openssl_obj_cert = openssl_x509_parse($cert_text);
  if (!$openssl_obj_cert) iError('error');

  $subject = OpenSSLGetSubjectString($openssl_obj_cert['subject']);
  $issuer = OpenSSLGetSubjectString($openssl_obj_cert['issuer']);
  $serial = $openssl_obj_cert['serialNumber'];
  $notbefore = $openssl_obj_cert['validFrom'];
  $notbefore_time_t = $openssl_obj_cert['validFrom_time_t'];
  $notbefore_datetime = date("Y-m-d H:i:s", $notbefore_time_t);
  $notafter = $openssl_obj_cert['validTo'];
  $notafter_time_t = $openssl_obj_cert['validTo_time_t'];
  $notafter_datetime = date("Y-m-d H:i:s", $notafter_time_t);

  print<<<EOS
subject=$subject<br>
issuer=$issuer<br>
serial=$serial<br>
notbefore=$notbefore<br>
notbefore_time_t=$notbefore_time_t<br>
notbefore_datetime=$notbefore_datetime<br>
notafter=$notafter<br>
notafter_time_t=$notafter_time_t<br>
notafter_datetime=$notafter_datetime<br>
$target <br>
EOS;

  list($hex, $ext) = preg_split("/\./", $name);
  //print_r($a);

  $pemfile = "/kistica/html.public/issued_v3/{$hex}.pem";
  $txtfile = "/kistica/html.public/issued_v3/{$hex}.txt";
  $crtfile = "/kistica/html.public/issued_v3/{$hex}.crt";

  //$ret = copy($tmpname, $pemfile);
  $cmd = "/bin/grep \"^[^ ]\" $tmpname | grep -v Certificate > $pemfile";
  exec($cmd);

  $cmd = "chmod 644 $pemfile";
  exec($cmd);

  $cmd = "/usr/bin/openssl x509 -in $pemfile -text > $txtfile";
  exec($cmd);

  $cmd = "/bin/cp $pemfile $crtfile";
  exec($cmd);



  # counter ���̺����� certid�� ������Ŵ
  $qry = "SELECT certid FROM counter";
  $row = DBQueryAndFetchRow($qry);
  $certid = $row['certid'] + 1;
  $qry = "UPDATE counter SET certid=certid+1";
  $ret = DBQuery($qry);

  $ctype = $form['ctype'];

  $qry = "INSERT INTO cert SET certid='$certid'"
        .",serial='$serial',subject='$subject'"
        .",vfrom='$notbefore_datetime',vuntil='$notafter_datetime'"
        .",ctype='$ctype'"
        .",status='issued'"
        .",cert='$cert_text'"
        .",csrid='$csrid'" # CSR ID�� CERT ���̺��� ����
        .",idate=NOW()";
# print("$qry");
  $ret = DBQuery($qry);
# print DBError();

  # CERT ID�� CSR���̺��� ����
  $qry = "UPDATE csr SET certid='$certid'"
        .",status='issued'"
        ." WHERE csrid='$csrid'";
# print("$qry");
  $ret = DBQuery($qry);
# print DBError();

  Redirect("csr.php");
  exit;
}



# CSR ����
else if ($mode == 'del') {
  $csrid = $form['csrid'];

/*
  $qry = "SELECT * FROM csr WHERE csrid='$csrid'";
  $row = DBQueryAndFetchRow($qry);
  if (!$row) iError("CSR $csrid not found");

  $certid = $row['certid'];

  $qry = "UPDATE cert SET status='upload' WHERE certid='$certid'";
  $ret = DBQuery($qry);
  print DBError();
*/

  $qry = "DELETE FROM csr WHERE csrid='$csrid'";
  $ret = DBQuery($qry);
  print DBError();

  Redirect("csr.php");
  exit;
}



#######################################################################

  include("head.php");

  $title = "CSR Management";
  ParagraphTitle($title);

  print<<<EOS
<style>
td.a  { background:#cccccc; text-align:center; margin:3 3 3 3px; }
td.b  { background:#eeeeee; text-align:center; margin:3 3 3 3px; }
td.bl { background:#eeeeee; text-align:left;   margin:3 3 3 3px; }
</style>
EOS;


  $qry = "SELECT count(*) AS count FROM csr";
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

  print<<<EOS
$pager

<table border='0' cellpadding='3' cellspacing='1' bgcolor='#999999'>
<tr>
 <td class='a'>#</td>
 <td class='a'>CSR.ID</td>
 <td class='a'>Subject Information</td>
 <td class='a'>Date</td>
 <td class='a'>Type</td>
 <td class='a'>Email</td>
 <td class='a'>Status</td>
 <td class='a'>CERT</td>
 <td class='a'>del</td>
</tr>
EOS;

  $qry = "SELECT * FROM csr ORDER BY idate DESC";
  $qry .= " LIMIT $start,$ipp";

  $ret = DBQuery($qry);
  $cnt = 0;
  while ($row = DBFetchRow($ret)) {
    $cnt++;
#   print_r($row);

    $csrid = $row['csrid'];

    $idate = $row['idate'];
    $idate = substr($idate, 0, 10);

    $certid = $row['certid'];
    if ($certid > 0) $cert_s =<<<EOS
<a href='cert.php?mode=view&certid=$certid'>CERT $certid</a>
EOS;
    else $cert_s = '';

    $email = $row['email'];
    print<<<EOS
<tr>
 <td class='b'>$cnt</td>
 <td class='b'>$csrid</td>
 <td class='bl'><a href='$env[self]?mode=view&csrid=$csrid'>{$row['forminfo']}</a></td>
 <td class='b' nowrap>$idate</td>
 <td class='b'>{$row['csrtype']}</td>
 <td class='b'><a href='mailto:$email'>{$email}</a></td>
 <td class='b'>{$row['status']}</td>
 <td class='b' nowrap>$cert_s</td>
 <td class='b'><a href="javascript:script_Question('$env[self]?mode=del&csrid=$csrid','Delete?')">del</a></td>
</tr>
EOS;
  }
  print<<<EOS
</table>
EOS;

  include("tail.php");
  exit;

?>
