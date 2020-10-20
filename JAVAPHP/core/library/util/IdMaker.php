<?php
/*
=============================================
* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : ModelMap.php
*  create date: 2010-12-9
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wangyuantao@coscoqd.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
 class IdMaker {   
      
    static function getId(){
        return date("YmdHis");
    }
    
}
?>