<?php
/* Copyright (c) 2011-2013 Create By WangYuantao
=============================================
*  Function   : TbConfig.php
*  create date: 2011-09-06 13:09:38
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wit521@gmail.com
*  Http       : www.java-php.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
$db_config = array(

     "host" => "hdm302080151.my3w.com",
     "username" => "hdm302080151",
     "password" => "zlg123456",
     "database" => "hdm302080151_db",
    /*"host" => "localhost",
    "username" => "root",
    "password" => "root",
    "database" => "db_firm",*/
     "dbprefix" => "db_",
     "tbprefix" => "tcs_",
     "encode" => "utf-8"

);

?>