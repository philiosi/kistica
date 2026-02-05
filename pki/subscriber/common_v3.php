<?php
# Error msg display setting
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

#Original Source
  unset($env);
  $env['prefix'] = '/kistica/html';
  include("$env[prefix]/include/common_v3.php");

  # Info
  $i_cn = $_SERVER['SSL_CLIENT_I_DN_CN'];
  $s_cn = $_SERVER['SSL_CLIENT_S_DN_CN'];
  $s_dn = $_SERVER['SSL_CLIENT_S_DN'];
  $m_serial = $_SERVER['SSL_CLIENT_M_SERIAL'];
  $m_serial = hexdec($m_serial);
  //print("$i_cn $s_cn $m_serial");


  # ?ø??? ??ȣ?? ?̿??Ͽ? webcert.dn�� ?˾Ƴ?
  $qry = "SELECT * FROM webcert WHERE dn='/$s_dn' AND serial='$m_serial'"
       ." AND authz='user'";

  //print($qry);
  $connect = new mysqli($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd'], $conf['dbname']);  
  if ($connect->connect_error){
     	Error("Unable to connect to MariaDB");
	//exit("Unable to connect to MariaDB: ".$connect->connect_error);
  }

  $result=$connect->query($qry);
  if( !$result ) {
  	iError( "Data query error:".$connect->error );
  }
  	
  $row = $result->fetch_assoc();
  
  if(!$row) iError("Access Denied");

  $result->free();
  $connect->close();

  $env['email'] = $row['email'];
  $env['role'] = 'SUBSCRIBER';
  $env['subscid'] = $row['subscid']; # subscriber table id

  // print_r($row);
  // $jRow = json_encode($row);
  // Console_log($jRow);

  $color1 = "#cccccc";
  $color2 = "#eeeeee";
  $color3 = "#999999";


?>
