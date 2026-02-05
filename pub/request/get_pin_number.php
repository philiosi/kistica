<?php

if ($_SERVER['HTTPS'] != 'on') {
  //print_r($_SERVER);
  $server = $_SERVER['SERVER_NAME'];
  $path = $_SERVER['SCRIPT_NAME'];
  $url = "https://$server:8443$path";
  print<<<EOS
<script>
document.location = "$url";
</script>
EOS;
  //header("Location: $url");
  exit;
};

unset($env);
$env['prefix'] = '/www2/kistica';
include("$env[prefix]/include/common.php");
$env['self'] = $_SERVER['SCRIPT_NAME'];


if ($mode == 'doget') {
  //print_r($form);
  $email = $form['email'];

function _GeneratePinNumber() {
  $pin = 0;
  while ($pin < 100000) {
    $r1 = rand();
    $r2 = rand();
    $r3 = rand();
    $md5 = md5("$r1:$r2:$r3");
    $cut = substr($md5, 0, 10);
    $pin = hexdec($cut);
    $pin = $pin % 100000000;
//print("$r1 $r2 $r3 $md5 $cut $pin\n");
  }
  return $pin;
}

  $pin = _GeneratePinNumber();
  //print($pin);

  $ipaddr = $_SERVER['REMOTE_ADDR'];

  # store the pin number into the database
  $qry = "INSERT INTO pin SET pin='$pin',email='$email',ipaddr='$ipaddr',idate=NOW()";
  $ret = DBQuery($qry);

  $to = $email;
  $subject = "[KISTI Grid CA] Your PIN Number";
  $header = "FROM: 'KISTI Grid CA'<ca@gridcenter.or.kr>\n";
  $header .= "CC: 'KISTI Grid CA'<ca@gridcenter.or.kr>\n";
  $message =<<<EOS

Your PIN Number is : $pin

You should write down the PIN number in the User Application Form.

If you have any question or problem, please send email to us.


EOS;
  mail($to, $subject, $message, $header);

  include("../head.php");

  $title = "Check Your Mail Box";
  ParagraphTitle($title);

  print<<<EOS
<p style="line-height:160%;">
Your PIN Number is sent to <b>$email</b>.<br>
Please check your mail box.<br>
Keep on the procedure in the <a href='http://ca.gridcenter.or.kr:8080/request/user_certificate.php'>User Certificate Request Instruction</a>.<br>
You should write down the PIN number in the User Application Form.<br>
</p>
EOS;

  include("../tail.php");
  exit;
}

  include("../head.php");

  $title = "Get Your PIN Number";
  ParagraphTitle($title);

  print<<<EOS
<p style="line-height:160%;">
Enter your e-mail address in the form.<br>
You will get a random PIN number (8-digit string) via the e-mail.<br>
</p>
<table border='0'>
<form name='form' action='$env[self]' method='post'>
<tr>
 <td>Your e-mail:</td>
 <td><input type='text' name='email' size='20' maxlength='100'></td>
 <td>
   <input type='submit' value='Get a PIN Number'>
   <input type='hidden' name='mode' value='doget'>
 </td>
</tr>
</form>
</table>
EOS;

  include("../tail.php");
  exit;

?>
