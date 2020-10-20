<?php
/* Copyright (c) 2013-2016 Create By WangYuantao
=============================================
*  Function   : ArticlesDAO.php
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

class ArticlesDAO extends BaseDAO {

    function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
        $this->tableName="tcs_articles";
        $this->fields = "ID,TYPE,TITLE,AUTHOR,CONTENT,SOURCE,IS_PUBLISH,IS_TOP,COUNTER,INMAN,INTIME";
        $this->primaryKey ="ID";
    }
    
    

}
?>