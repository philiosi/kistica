<?php

  include("$env[prefix]/config/config_v3.php");		//DB definition
  include("$env[prefix]/include/func.php");		//mysql and misc function definition
  /* misc
	iError($msg, $go_back=1, $win_close=0)
	Redirect($url, $http=true)
	Qstr($form)
  */

  /* mysql
	DBError(), DBConnect(), DBConnectSub($dbhost, $dbuser, $dbpasswd, $dbname)
	DBClose(), DBQuery($query), DBFetchRow($result), DBQueryAndFetchRow($query)
	DBFreeResult($result)
  */


  # MySQL ������ ���̽� ����
  if (DBConnect()) {
    iError('database connection error');
  }

# GET/POST ��� ��� ����̵� $form �� ����ȴ�.
# if ($_SERVER['REQUEST_METHOD'] == 'GET') {
#   $form = $_GET;
# } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
#   $form = $_POST;
# }

/*
if (isset($_REQUEST['mode'])) {
  $form = $_REQUEST;
  $mode = $form['mode'];
}
*/

$form = $_REQUEST;
$mode = $form['mode'];
#var_dump('This is '.$mode);
/*
else {
  $form = [
    'mode' => 'list'
  ];
}*/
  /*
  $jRequest = json_encode($_REQUEST); 
  echo "
                <script type=\"text/javascript\">
                        var e = '<?= $jRequest ?>' ; console.log(e);
                </script>
        ";
	*/  

//print("request :");
//print_r($_REQUEST);
//	iError($form,0,0);
/*	print("<script>\n");
	print("console.log(\"$msg\");\n");
	print("</script>\n");  */

  $env['self'] = $_SERVER['SCRIPT_NAME'];
  //var_dump('Here is '.$env['self']);
//  iError($env['self']);

?>
