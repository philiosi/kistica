<?php

# form
if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'GET') {
  $form = $HTTP_GET_VARS;
} elseif ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST') {
  $form = $HTTP_POST_VARS;
}

  $prefix = '../..';
  include("$prefix/head.php");

  $title = "CP/CPS Revision History";
  ParagraphTitle($title);

  $section = $form['section'];
  if ($section != '') $url = "cps.html#$section";
  else $url = "cps.html";

  print<<<EOS
<table border='0' width='600'><tr><td align='left'>
<!--
<a href='$url'>Open in a new window</a>
<br>
-->
<a href='cps.html'>- beta version (2006 Nov. 20)</a>
<br>
<a href='KISTI-CPCPS-2.0-beta-1212.html'>- beta version (2006 Dec. 12)</a>
<a href='KISTI CP-CPS-v2.0.doc'>(doc format)</a>
<a href='cps-1212.html'>(for comparison between 1 and 2)</a>
<br>
<a href='KISTI-CPCPS-2.0-beta2-1220.html'>- beta version (2006 Dec. 20)</a>
<br>
<a href='KISTI-CPCPS-2.0-beta3-0108.html'>- beta version (2007 Jan. 8)</a>
<br>
<a href='KISTI-CPCPS-2.0-beta4-0115.html'>- beta version (2007 Feb. 13)</a>
<br>
<a href='KISTI-CPCPS-2.0-beta5-0214.html'>- beta version (2007 Mar. 2)</a>
<br>
<a href='KISTI-CPCPS-2.0-beta6-0302.html'>- beta version (2007 Mar. 7)</a>
 (accord with RFC3647 structure)
<br>
<a href='KISTI-CPCPS-2.0-beta7-0510.html'>- beta version (2007 May 10)</a>
<br>
<a href='KISTI-CPCPS-2.0-beta8-0520.html'>- beta version (2007 May 20)</a>
<br>
<a href='KISTI-CPCPS-2.0-beta9-0523.html'>- beta version (2007 May 23)</a>
<br>
<a href='KISTI-CPCPS-2.0-beta10-0525.html'>- beta version (2007 May 25)</a>
<br>
<a href='audit_check_list-0528.htm'>- Audit Checklist (2007 May 28)</a><br>
<a href='KISTI-CPCPS-2.0-beta11-0613.html'>- beta version (2007 Jun 18)</a><br>
<a href='audit_check_list-0618.htm'>- Audit Checklist (2007 June 18)</a><br>
<a href='rfc_compare.htm'>- RFC comparison</a><br>
<a href='KISTI-CPCPS-2.0-beta12-0627.html'>- beta version (2007 Jun 27)</a><br>
<a href='KISTI-CPCPS-2.0-beta13-0704.html'>- beta version (2007 July 5)</a><br>
<a href='KISTI-CPCPS-2.0-beta14-0710.html'>- beta version (2007 July 12)</a><br>
<a href='KISTI-CPCPS-2.0-beta15-0716.html'>- beta version (2007 July 16)</a><br>
<a href='KISTI-CPCPS-2.0-beta16-0718.html'>- beta version (2007 July 18)</a><br>
<a href='KISTI-CPCPS-2.0-beta17-0720.html'>- beta version (2007 July 20)</a><br>
<a href='KISTI-CPCPS-2.0-beta-final.html'>- beta final version (2007 July 20)</a>
(waiting for vote)<br>

<br>
</td></tr></table>

<!--
<iframe src='$url' frameborder='0' width='100%' height='600' name='cps'></iframe>
-->
EOS;

  include("$prefix/tail.php");

?>
