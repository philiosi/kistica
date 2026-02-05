<?php


# display an message through console.log
function Console_log($data){
	echo "<script> console.log('PHP_Console:".$data."');</script>";
};

/*
function store_localstorage($data){
  echo "<script> localStorage.setItem(certid, ".$data['certid'].");</script>";
};*/


# display an error message and terminate program
function iError($msg, $go_back=1, $win_close=0) {
  $msg = preg_replace("/\n/", "\\n", $msg);
  print("<script>\n");
  print("alert(\"$msg\");\n");
  if ($go_back) print("history.go(-1);\n");
  if ($win_close) print("window.close();");
  print("</script>\n");
  exit;
}


function Redirect($url, $http=true) {
  if ($http) {
    header("Location: $url");
    exit;
  } else {
    print("<script>\n");
    print("window.location='$url';\n");
    print("</script>\n");
    exit;
  }
}


# $form ������ ���� ������Ʈ���� ������ش�.
function Qstr($form) {
  global $env;
# if (!is_array($form)) $form = array();
  $retval = "?d";
  ksort($form);
  while (list($k, $v) = each($form)) {
    if ($v == '') continue;
    $retval .= "&$k=$v";
  # print("$k, $v");
  }
  return $retval;
}

function Pager_s($url, $page, $total, $ipp) {
  global $conf, $env;
  $html = '';

  $btn_prev = "<img src='/img/calendar/l.gif' border=0 width=11 height=11>";
  $btn_next = "<img src='/img/calendar/r.gif' border=0 width=11 height=11>";
  $btn_prev10 = "<img src='/img/calendar/l2.gif' border=0 width=11 height=11>";
  $btn_next10 = "<img src='/img/calendar/r2.gif' border=0 width=11 height=11>";

  $last = ceil($total/$ipp);
  if ($last == 0) $last = 1;

  $start = floor(($page - 1) / 10) * 10 + 1;
  $end = $start + 9;

  $html .= "<table border='0' cellpadding='2' cellspacing='0'><tr>"; # table 1

  $attr1 = " onmouseover=\"this.className='pager_on'\""
         ." onmouseout=\"this.className='pager_off'\""
         ." class='pager_off' align='center' style='cursor:pointer;'";
  $attr2 = " onmouseover=\"this.className='pager_sel_on'\""
         ." onmouseout=\"this.className='pager_sel_off'\""
         ." class='pager_sel_off' align='center' style='cursor:pointer;'";

  # previous link
  if ($start > 1) {
    $prevpage = $start - 1;
    $html .= "<td$attr1 align=center onclick=\"script_Go('$url&page=$prevpage')\"><a href='$url&page=$prevpage'>$btn_prev10</a></td>\n";
  } else $html .= "<td align=center class='pager_static'>$btn_prev10</td>\n";

  if ($page > 1) {
    $prevpage = $page - 1;
    $html .= "<td$attr1 align=center onclick=\"script_Go('$url&page=$prevpage')\"><a href='$url&page=$prevpage'>$btn_prev</a></td>\n";
  } else $html .= "<td align=center class='pager_static'>$btn_prev</td>\n";


  if ($end > $last) $end = $last;
 $html .= "</td>";
  for ($i = $start; $i <= $end; $i++) {
    $s = "$i";
    if ($i != $page) {
      $html .= "<td$attr1 onclick=\"script_Go('$url&page=$i')\">$s</td>\n";
    } else {
      $html .= "<td$attr2>$s</td>\n";
    }
  }

  # next link
  if ($page < $last) {
    $nextpage = $page + 1;
    $html .= "<td$attr1 align=center onclick=\"script_Go('$url&page=$nextpage')\"><a href='$url&page=$nextpage'>$btn_next</a></td>\n";
  } else $html .= "<td align=center class='pager_static'>$btn_next</td>\n";

  if ($end < $last) {
    $nextpage = $end + 1;
    $html .= "<td$attr1 align=center onclick=\"script_Go('$url&page=$nextpage')\"><a href='$url&page=$nextpage'>$btn_next10</a></td>\n";
  } else $html .= "<td align=center class='pager_static'>$btn_next10</td>\n";

  $html .= "</tr></table>\n";

  return $html;
}

?>
