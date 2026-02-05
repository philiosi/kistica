<?php

# form
$form = $_REQUEST;


  include("../head.php");

  $title = "CP/CPS";
  ParagraphTitle($title);

  $section = $form['section'];
  if ($section != '') $url = "KISTI-CPCPS-2.0.html#$section";
  else $url = "KISTI-CPCPS-2.0.html";

  print<<<EOS
<table border='0' width='100%'>
  <tr>
    <td style='color:black; text-align:left'>
      <a href='KISTI-CA_CPCPS-3.1.pdf'>Certificate Policy and Certification Practice Statement (v3.1) for KISTI CA</a> (Published: September 25, 2024)
      <br><br>
      <a href='$url'>Certificate Policy and Certification Practice Statement (v2.0) for KISTI GRID CA</a> (Published: July 20, 2007)
      <br><br>
    </td>
  </tr>
</table>

<!-- <iframe src='$url' frameborder='0' width='100%' height='600' name='cps'></iframe>
<br>
<br>
<a href='history/cps-070725.php'>Revision history of this document.</a>
<br>
<br>
-->
EOS;

  include("../tail.php");

?>
