<?php
/* Copyright (c) 2013-2016 Create By WangYuantao
=============================================
*  Function   : FeedbackDAO.php
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

class FeedbackDAO extends BaseDAO {

    function __construct(){
        parent::__construct();
        $this->className = __CLASS__;
        $this->tableName="tcs_feedback";
        $this->fields = "id,name,email,phone,fax,address,zip,content,reply_content,reply_man,reply_time,intime";
        $this->primaryKey ="id";
    }
    
    function getTotalNum(){
        $this->methodName = __METHOD__;
        $rows = $this->getList();
        return count($rows);
    }


}
?>
