<?php
/* Copyright (c) 2013-2016 Create By WangYuantao
=============================================
*  Function   : SysinfoDAO.php
*  create date: 2013-011-19 16:01:44
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wit521@gmail.com
*  Http       : www.java-php.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}

import("com.dao.BaseDAO");

class SysinfoDAO extends BaseDAO {

    function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
        $this->tableName="tb_qdmtc_sysinfo";
        $this->fields = "ID,SYS_NAME,SYS_LOGO,SYS_DOMAIN,SYS_COUNTER,SYS_ADDRESS,SYS_ZIP,SYS_SERVICE_MAIL,
        SYS_PRESIDENT_MAIL,SYS_APPLYONLINE_COUNTER,SYS_CSS,SYS_IMAGES,SYS_LIBRARY,SYS_TEMPLATE,
        SYS_SERVER,SYS_DBNAME,SYS_DBUSER,SYS_DBPASS";
        $this->primaryKey ="ID";
    }
    

}
?>