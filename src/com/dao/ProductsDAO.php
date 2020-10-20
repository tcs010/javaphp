<?php
/* Copyright (c) 2013-2016 Create By WangYuantao
=============================================
*  Function   : ProductsDAO.php
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

class ProductsDAO extends BaseDAO {

    function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
        $this->tableName="tcs_products";
        $this->fields = "ID,CAT_ID,(select NAME from tcs_products_cat where tcs_products_cat.ID=CAT_ID)CAT_NAME,PIC,TITLE,AUTHOR,CONTENT,SOURCE,IS_PUBLISH,IS_TOP,COUNTER,INMAN,INTIME";
        $this->primaryKey ="ID";
    }
    
    function getTotalNumByCat($cat){
        $this->methodName = __METHOD__;
        $this->conditionString = "where TYPE='".$cat."'";
        $rows = $this->getList();
        return count($rows);
    }


}
?>