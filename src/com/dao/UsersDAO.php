<?php
/* Copyright (c) 2013-2016 Create By WangYuantao
=============================================
*  Function   : UsersDAO.php
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

class UsersDAO extends BaseDAO {

    function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
        $this->tableName="tcs_sysusers";
        $this->fields = "ID,USER_ID,NAME,PASSWORD,TYPE_CODE,INMAN,INTIME";
        $this->primaryKey ="ID";
    }
    
    //获取用户信息
    function getUserInfo($username,$password){
        $this->methodName = __METHOD__;
        $sql = "select ".$this->fields." from ".$this->tableName." where USER_ID='".$username."' and PASSWORD=md5('".$password."') ";
        return $this->execSql($sql);
        
    }
    //检查登陆状态
    function checkUserLogin(){
        $this->methodName = __METHOD__;
        $sql = "select ".$this->fields." from ".$this->tableName." where USER_ID='".$username."' and PASSWORD=md5('".$password."') ";
        return $this->execSql($sql);
    }
    

}
?>