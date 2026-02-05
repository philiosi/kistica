<?php

  unset($env);
  $env['prefix'] = '/var/www/html';
  include("$env[prefix]/include/common.php");
  
  # 권한확인
  // 클라이언트 인증서 DN 과 SERIAL 확인
  $i_cn = $_SERVER['SSL_CLIENT_I_DN_CN'];
  $s_cn = $_SERVER['SSL_CLIENT_S_DN_CN'];
  $s_dn = $_SERVER['SSL_CLIENT_S_DN'];
  $m_serial = $_SERVER['SSL_CLIENT_M_SERIAL'];
  $m_serial = hexdec($m_serial);

  //print("$i_cn $s_cn $m_serial");
  $qry = "SELECT * FROM webcert"
   ." WHERE dn='/$s_dn' AND serial='$m_serial'"
   ." AND authz='ca'";



  $ret = mysql_query($qry);
  $row = mysql_fetch_array($ret);

//  print($qry);
  Console_log($qry);
  if (!$row) iError("Access denined", 0, 0);

  $env['role'] = 'CA';

?>
