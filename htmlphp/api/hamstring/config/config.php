<?php
include("constant.php");

//DB CONNECTION
$con = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);

if(!$con){
    echo DB_CONNECTION_ERROR;
}else{
    mysql_select_db(DB_NAME,$con);
}

?>