<?php

# form
if ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'GET') {
  $form = $HTTP_GET_VARS;
} elseif ($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST') {
  $form = $HTTP_POST_VARS;
}


  include("../head.php");

  $title = "Minimum CA Requirements";
  ParagraphTitle($title);

# $section = $form['section'];
# if ($section != '') $url = "cps.html#$section";
# else $url = "cps.html";
  $url = "min_requirement.html";

  print<<<EOS
<table border='0' width='600'><tr><td align='right'>
<a href='$url' target='_blank'>Open in a new window</a><br>
</td></tr></table>
<iframe src='$url' frameborder='0' width='100%' height='600'></iframe>
EOS;

  include("../tail.php");
  exit;

?>
