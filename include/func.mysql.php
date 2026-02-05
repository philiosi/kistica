<?php

# get the last error message if exists
function DBError() {
  $err = mysql_error();
  if ($err) return $err;
  else return;
}

# connect to a mysql server using configuration in conf array
# return 0 if success, return true if failed
function DBConnect() {
  global $conf;
  return DBConnectSub($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd'], $conf['dbname']);
}

function connectDB() {
  global $conf;
  $conn = new mysqli($conf['dbhost'], $conf['dbuser'], $conf['dbpasswd'], $conf['dbname']);
  return $conn;
}
# connect to a mysql server
# return 0 if success, return true if failed
function DBConnectSub($dbhost, $dbuser, $dbpasswd, $dbname) {

  $connect = mysql_connect($dbhost, $dbuser, $dbpasswd) ;
  if (!$connect) return 1;
  
  $ret = mysql_select_db($dbname, $connect);
  if (!$ret) return 1;

  #printf("Initial character set: %s\n", $mysql->character_set_name()); 

  return 0;
}

# disconnect from database server
function DBClose() {
  mysql_close();
}

# DB Query
function DBQuery($query) {
#$logging = new Logging(); # default logging file, don't append
#$logging->log("$query");
  //print("$query<br>");
  $result = mysql_query($query);
  return $result;
}

# DB Fetch Row
function DBFetchRow($result) {
  #echo("*$result*");
  $row = mysql_fetch_array($result); 
  return $row;
}

# DB Query and Fetch Row
function DBQueryAndFetchRow($query) {
  $result = mysql_query($query);
  //print($query);
  $row = mysql_fetch_array($result);
  mysql_free_result($result);
  return $row;
}

# free result
function DBFreeResult($result) {
  if (is_resource($result)) mysql_free_result($result);
}

?>
