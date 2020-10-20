<?php
/* Copyright (c) 2013-2016 Create By WangYuantao
=============================================
*  Function   : AuthorsDAO.php
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

class AuthorsDAO extends BaseDAO {

    function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
        $this->tableName="tcs_authors";
        $this->fields = "ID,USER_ID,FUNCTION_ID,INMAN,INTIME";
        $this->primaryKey ="ID";
    }
    
    function deleteByUserId($userId){
         $sql = "delete from ".$this->tableName." where USER_ID='".$userId."'";
         $this->execSql($sql);
    }
    

}
?>