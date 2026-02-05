<?php

  unset($env);
  $env['prefix'] = '/kistica/html';
  include("$env[prefix]/include/common_v3.php");
  
  # ����Ȯ��
  // Ŭ���̾�Ʈ ������ DN �� SERIAL Ȯ��
  $i_cn = $_SERVER['SSL_CLIENT_I_DN_CN'];
  $s_cn = $_SERVER['SSL_CLIENT_S_DN_CN'];
  $s_dn = $_SERVER['SSL_CLIENT_S_DN'];

  //echo $i_cn;
  //echo $s_cn;
  //echo $s_dn;

  //iError($s_dn) ;
  $m_serial = $_SERVER['SSL_CLIENT_M_SERIAL'];
  $m_serial = hexdec($m_serial);

  //print("$i_cn $s_cn $m_serial");
  //$qry = "SELECT * FROM webcert"
  // ." WHERE dn='/$s_dn' AND serial='$m_serial'"
  // ." AND authz='ca'";
  $qry = "SELECT * FROM webcert WHERE serial='$m_serial' AND authz='ca'";
  $connect = new mysqli($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd'], $conf['dbname']);   // 4th arg : "somedata"

  if ($connect->connect_error){
	  iError("DB : Unable to connect to MariaDB");
  }
  
  $result=$connect->query($qry);
  if( !$result ) {
        exit( "Data query error:".$connect->error );
  }
    
  $row = $result->fetch_assoc();
  $result->free();
  $connect->close();

  $_role = $row['authz'];
  

  if( $row['authz'] != 'ca'){
    iError("Access denied!!!!! $_role");
  }

  $env['role'] = 'CA';
  
	/*
  	$jsonServer = json_encode($_SERVER);
	echo "
		<script type=\"text/javascript\">
			var e = '<?= $jsonServer ?>' ; console.log(e);
		</script>
	";

  print<<<EOS
  
  EOS;
	*/

?>


