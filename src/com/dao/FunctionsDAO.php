<?php
/* Copyright (c) 2013-2016 Create By WangYuantao
=============================================
*  Function   : FunctionsDAO.php
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

class FunctionsDAO extends BaseDAO {

    function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
        $this->tableName="tcs_functions";
        $this->fields = "ID,FUNCTION_NAME,URL,TARGET,ADMIN_URL,ADMIN_TARGET,FATHER_ID,ORDER_ID,ENABLE,INMAN,INTIME";
        $this->primaryKey ="ID";
    }
    
    function getByFatherIdAll($fatherId){
        $this->methodName = __METHOD__;
        $this->conditionString = " where FATHER_ID='".$fatherId."' ";
        $this->orderString = "order by ENABLE desc,FATHER_ID,ORDER_ID";
        return $this->getList();
    }
    function getByFatherId($fatherId){
        $this->methodName = __METHOD__;
        $this->conditionString = " where FATHER_ID='".$fatherId."' and ENABLE=1";
        $this->orderString = "order by ENABLE desc,FATHER_ID,ORDER_ID";
        return $this->getList();
    }
    
    function getNameByPK($pk){
        $this->methodName = __METHOD__;
        $row = $this->getByPk($pk);
        if(count($row)>0){
            return $row["FUNCTION_NAME"];
        }
        return "";
    }

}
?>