<?php

unset($env);
$env['prefix'] = '/kistica/html';
include("$env[prefix]/include/common_v3.php");

  # 권한확인
  $i_cn = $_SERVER['SSL_CLIENT_I_DN_CN'];
  $s_cn = $_SERVER['SSL_CLIENT_S_DN_CN'];
  $s_dn = $_SERVER['SSL_CLIENT_S_DN'];
  $m_serial = $_SERVER['SSL_CLIENT_M_SERIAL'];
  $m_serial = hexdec($m_serial);
  // print("$i_cn $s_cn $s_dn $m_serial");

//  $qry = "SELECT * FROM webcert WHERE dn='/$s_dn' AND serial='$m_serial'"
//       ." AND authz='ra'";
  
  $qry = "SELECT * FROM webcert WHERE serial='$m_serial' AND authz='ra'";

  // print("$qry");
  
  $row = DBQueryAndFetchRow($qry);
  
  // print_r($row);
  if (!$row) iError("Access denined");

  $env['role'] = 'RA';

?>
