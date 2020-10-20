<?php
/* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : AppException.php
*  create date: 2010-12-9
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wangyuantao@coscoqd.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
require_once(APP_CORE_EXCEPTION_PATH."/BaseException.php");
require_once(APP_CORE_EXCEPTION_PATH."/FileException.php");
require_once(APP_CORE_EXCEPTION_PATH."/SecurityException.php");
require_once(APP_CORE_EXCEPTION_PATH."/SQLException.php");

?>
