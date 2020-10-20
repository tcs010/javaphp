<?php
/* Copyright (c) 2013-2016 Create By WangYuantao
=============================================
*  Function   : SysusersTypeDAO.php
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

class SysusersTypeDAO extends BaseDAO {

    function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
        $this->tableName="tb_qdmtc_sysusers_type";
        $this->fields = "ID,TYPE_CODE,TYPE_NAME,INMAN,INTIME";
        $this->primaryKey ="ID";
    }
    

}
?>