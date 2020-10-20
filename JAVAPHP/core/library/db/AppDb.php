<?php
/* Copyright (c) 2011-2013 Create By WangYuantao
=============================================
*  Function   : AppDb.php
*  create date: 2011-10-13 13:09:38
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wit521@gmail.com
*  Http       : www.java-php.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
class AppDb extends db_mysql{
	public function __construct(){
        include(APP_DB_CONFIG_FILE);
        $this->Host     = $db_config["host"];
        $this->Database = $db_config["database"];
        $this->User     = $db_config["username"];
        $this->Password = $db_config["password"];
        $this->Dbencode = $db_config["encode"]; 
	}
}
?>