<?php

  include("common.php");


#####################################
### {{{ CSR 업로드 처리
#####################################
if ($mode == 'request') {
# print_r($form);
  $dnc_o = $form['dnc_o']; // Organization
  $dnc_cn = $form['dnc_cn']; // CN
  $csr = $form['csr']; # CSR text
  $rno = $form['rno']; # random number

  $email = $env['email']; # subscriber's email of WACC


  # cannot duplicate user certificate request
  $qry = "SELECT * FROM csr"
 ." WHERE csrtype='user'"
 ." AND status='issued'" # 이미 발급한 것이 있으면
 ." AND email='$email'";
# print $qry;

  $row = DBQueryAndFetchRow($qry);
  //print DBError();
  if ($row) {
    iError("Your CSR has not been processed because you already have uploaded a user certificate request.\\n"
          ."You can not duplicate user certificate request more than once.");
    exit;
  }

  $forminfo =<<<EOS
O=$dnc_o|CN=$dnc_cn
EOS;
  $forminfo = addslashes($forminfo);

  $qry = "INSERT INTO csr SET csr='$csr'"
        .",forminfo='$forminfo'"
        .",csrtype='user'"
        .",email='$email'"
        .",status='upload'"
        .",certid=-1"
        .",idate=NOW()";
# print("$qry");
  $ret = DBQuery($qry);

  $to = $email;
  $subject = "[KISTI Grid CA] CSR (certificate signing request) has been sent to KISTI CA";
  $header = "FROM: 'KISTI Grid CA'<ca@gridcenter.or.kr>\n";
  $header .= "CC: 'KISTI Grid CA'<ca@gridcenter.or.kr>\n";
  $message =<<<EOS

Your CSR has been requestd to KISTI CA.

KISTI CA will review your CSR, before issuing your certificate.

After KISTI CA issue your certificate, a notification e-mail will be
sent to this e-mail address.

Issued certificates can be downloaded from the KISTI CA web site.

If you have any question or problem, please send email to us.

Your CSR is as follows:
--------------------------------------------------------
$csr
--------------------------------------------------------

EOS;
  mail($to, $subject, $message, $header);

  include("head.php");
  print<<<EOS
Your CSR has been requestd to KISTI CA.<br>
<br>
KISTI CA will review your CSR, before issuing your certificate.<br>
<br>
Your CSR is sent to your email <b>$email</b>.<br>
<br>
After KISTI CA issue your certificate, a notification e-mail will be sent to you.<br>
<br>
EOS;

  include("tail.php");
  exit;
}
#####################################
### }}} CSR 업로드 처리
#####################################



#####################################
### {{{ CSR 신청양식
#####################################

  include("head.php");

  $title = "Request User Certificate";
  ParagraphTitle($title);

  $subscid = $env['subscid'];
# print $subscid;


  $qry = "SELECT * FROM subscriber WHERE id='$subscid'";
  $row = DBQueryAndFetchRow($qry);
# print_r($row);
# if (!$row) iError("Can not found subscriber id");
  # common name
  $cn = sprintf("%s %s %s", $row['perid'], $row['firstname'], $row['lastname']); 

  print<<<EOS
<p style="line-height:160%;">
<b>Step 1</b>. Fill the following form, and click the 'Generate CSR' button.
  Visual Basic Script will create a CSR, which will be shown in the CSR text area of the step 2.<br>
</p>

<style>
td.a { background:$color1; width:120; height:30; text-align:right; }
td.b { background:$color2; width:400; height:30; text-align:left; }
td.c { background:$color1; width:520; height:30; text-align:center; }
</style>

<table border='0' cellpadding='3' cellspacing='1' bgcolor='$color3'>
<form name='form' method='post' action='$env[self]'>
<tr>
 <td class='a'>Country:</td>
 <td class='b'>KR</td>
</tr>
<tr>
 <td class='a'>Organization:</td>
 <td class='b'>KISTI</td>
</tr>
<tr>
 <td class='a'>Organization:</td>
 <td class='b'>GRID</td>
</tr>
EOS;

  $m_serial = $_SERVER['SSL_CLIENT_M_SERIAL'];
  $m_serial = hexdec($m_serial); # 16진수를 10진수로 변환

  $qry = "SELECT w.*, s.*"
     ." FROM webcert w"
     ." LEFT JOIN subscriber s ON w.subscid=s.id"
     ." WHERE w.serial='$m_serial'";
# print $qry;
  $row = DBQueryAndFetchRow($qry);
# print("<pre>");
# print_r($row);
# print("</pre>");
  $org = $row['org'];
   

/*
  # subscriber 테이블에서 org를 그룹으로 가져온다.
  $html = '';
  $qry = "SELECT org FROM subscriber GROUP BY org";
# print("$qry");
  $ret = DBQuery($qry);
# print DBError($ret);
  while ($row = DBFetchRow($ret)) {
    $org = $row['org'];
    $html.=<<<EOS
<option value='$org'>$org</option>
EOS;
  }
*/

//$cn = "12345678 KISTI";
  print<<<EOS
<tr>
 <td class='a'>Organization:</td>
 <td class='b'>
$org
<input type='hidden' name='dnc_o' value='$org'>
 </td>
</tr>
<tr>
 <td class='a'>Common Name:</td>
 <td class='b'>
$cn
<input type='hidden' name='dnc_cn' value='$cn' readonly><br>
 </td>
</tr>
<tr>
 <td class='c' colspan='2'>
   <input type='hidden' name='mode' value='request'>
   <input type='button' value=' Generate CSR ' name='btn_gencsr'
     onclick="vbGenerateCSR" language="VBScript">
 </td>
</tr>
</table>
<br>

<p style="line-height:160%;">
<b>Step 2</b>. Click the 'Upload CSR' button to upload your CSR.<br>
</p>

<table border='0' cellpadding='3' cellspacing='1' bgcolor='$color3'>
<tr>
 <td class='a'>CSR:</td>
 <td class='b'>
  <textarea name='csr'  cols='60' rows='10' readonly></textarea>
 </td>
</tr>
EOS;
  $rno = rand(); # random number
  print<<<EOS
<tr>
 <td class='c' colspan='2'>
   <input type='hidden' name='mode' value='request'>
   <input type='hidden' name='rno' value='$rno'>
   <input type='button' name='ubtn' value=' Upload CSR '
      onclick="upload_csr()" disabled>
 </td>
</tr>
</form>
</table>
EOS;

#<!--
#<object classid="clsid:43F8F289-7A20-11D0-8F06-00C04FC295E1"
#   codebase="xenroll.dll" id=Enroll>
#</object>
#-->
  print<<<EOS
<OBJECT id='Enroll'
 codeBase="/xenroll.dll#Version=5,131,3659,0"
 classid="clsid:127698e4-e730-4e5c-a2b1-21490a70c8a1"></OBJECT>
<script language="VBScript">
Sub vbGenerateCSR

  Dim Form
  Set Form = document.form

  if (Form.dnc_o.value = "") Then
    MsgBox ("Please Fill the Form")
    Exit Sub
  end if
  if (Form.dnc_cn.value = "") Then
    MsgBox ("Please Fill the Form")
    Exit Sub
  end if

' Form.dnc_o.readonly = 1
  Form.dnc_cn.readonly = 1
  Form.btn_gencsr.disabled = 1

  szName = "C=KR;O=KISTI;O=GRID"
  szName = szName + ";O=" & Form.dnc_o.value
  szName = szName + ";CN=" & Form.dnc_cn.value
' szName = szName + "; 1.2.840.113549.1.9.1=" & Form.email.value
' MsgBox szName

  Enroll.KeySpec = 1
  Enroll.GenKeyFlags = &h08000003

  Enroll.providerType = 1
  Enroll.providerName = "Microsoft Enhanced Cryptographic Provider v1.0"
  Enroll.HashAlgorithm = "MD5"
  
  sz10 = Enroll.CreatePKCS10(szName,"1.3.6.1.4.1.14305.1.1.1.1.2")
' if (theError) Then
'   MsgBox theError
' end if 
  if (sz10 = Empty OR theError <> 0) Then
    sz = "The error '" & Hex(theError) & "' occurred." & _
         chr(13) & chr(10) & _
         "Your credentials could not be generated."
    result = MsgBox(sz, 0, "Credentials Enrollment")
    Exit Sub
  else
    Form.csr.value = sz10
    Form.ubtn.disabled = False
    MsgBox ("CSR has been generated")
'   Form.submit()
  end if

End Sub
</script>
<script language="JavaScript">
function upload_csr() {
  var form = document.form;
  if (form.csr.value == '') {
    alert('You should generate CSR');
    return;
  }
  form.submit();
}
</script>
EOS;

  include("tail.php");
#####################################
### }}} CSR 신청양식
#####################################


?>
