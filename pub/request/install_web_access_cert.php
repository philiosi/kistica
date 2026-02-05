<?php

  include("../head.php");

  $title = "How to Install Web Access Client Certificate in the Browser";
  ParagraphTitle($title);

  print<<<EOS
<style>
/*
// http://www.neuroticweb.com/recursos/css-rounded-box/index.php?color=FFCC66&fondo=eeeeee
*/
/* set millions of background images */
.rbroundbox { background: url(nt.gif) repeat; }
.rbtop div { background: url(tl.gif) no-repeat top left; }
.rbtop { background: url(tr.gif) no-repeat top right; }
.rbbot div { background: url(bl.gif) no-repeat bottom left; }
.rbbot { background: url(br.gif) no-repeat bottom right; }

/* height and width stuff, width not really nessisary. */
.rbtop div, .rbtop, .rbbot div, .rbbot {
width: 100%;
height: 7px;
font-size: 1px;
}
.rbcontent { margin: 0 7px; }
.rbroundbox { width:250px; margin: 1em auto; }
</style>


<b>Microsoft Internet Explorder</b><br>

<div class="rbroundbox">
  <div class="rbtop"><div></div></div>
    <div class="rbcontent">
  <a href='install_web_access_client_cert_english.pdf'>
  <div id='down1' style='border:0px solid; display:inline; padding:2 2 2 2px; margin:5 5 5 5px;'>
  <span style='border:0px solid; vertical-align:middle;'>
  <img src='pdf_icon.png' width=32 height=32 border='0' align='middle'>
  </span>
  <span style='border:0px solid; vertical-align:middle;'>
  Instruction in English (pdf)
  </span>
  </div>
  </a>
    </div><!-- /rbcontent -->
  <div class="rbbot"><div></div></div>
</div><!-- /rbroundbox -->

<div class="rbroundbox">
  <div class="rbtop"><div></div></div>
    <div class="rbcontent">
  <a href='install_web_access_client_cert.pdf'>
  <div id='down1' style='border:0px solid; display:inline; padding:2 2 2 2px; margin:5 5 5 5px;'>
  <span style='border:0px solid; vertical-align:middle;'>
  <img src='pdf_icon.png' width=32 height=32 border='0' align='middle'>
  </span>
  <span style='border:0px solid; vertical-align:middle;'>
  Instruction in Korean (pdf)
  </span>
  </div>
  </a>
    </div><!-- /rbcontent -->
  <div class="rbbot"><div></div></div>
</div><!-- /rbroundbox -->

<p style="line-height:200%;">
1. Double-click the client.p12 file to install a web access client certificate.<br>
2. Check the file path and click 'Next'.<br>
3. Input your PIN number in the password input.<br>
4. Finish the Certificate Import Wizard.<br>
</p>
EOS;


  include("../tail.php");

?>
