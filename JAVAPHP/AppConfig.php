<?php
/* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : config.php
*  create date: 2010-12-9
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wangyuantao@coscoqd.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
//框架核心代码路径
define("APP_CONFIG_PATH", dirname(__FILE__));
define("APP_CORE_PATH", APP_CONFIG_PATH."/core");
define("APP_CORE_LIB_PATH", APP_CORE_PATH."/library");
define("APP_CORE_EXCEPTION_PATH", APP_CORE_PATH."/exception");
define("APP_CORE_I18N_PATH", APP_CORE_PATH."/i18n");
define("APP_CORE_ACTION_PATH", APP_CORE_PATH."/library/action");
define("APP_CORE_DAO_PATH", APP_CORE_PATH."/library/dao");
define("APP_CORE_VO_PATH", APP_CORE_PATH."/library/vo");
define("APP_CORE_DATABASE_PATH", APP_CORE_PATH."/library/db");
define("APP_CORE_UTIL_PATH", APP_CORE_PATH."/library/util");
define("APP_THIRDPARTY_PATH", APP_CONFIG_PATH."/thirdparty");
define("APP_SMARTY_PATH", APP_THIRDPARTY_PATH."/smarty");
//项目代码路径
define("APP_SRC_PATH", APP_CONFIG_PATH."/../src");
define("APP_COM_PATH", APP_SRC_PATH."/com");
define("APP_ACTION_PATH", APP_COM_PATH."/action");
define("APP_DAO_PATH", APP_COM_PATH."/dao");
define("APP_DB_PATH", APP_COM_PATH."/db");
define("APP_VO_PATH", APP_COM_PATH."/vo");
//echo dirname(__FILE__);

define("APP_WEBROOT_PATH", "WebRoot");
define("APP_TEMPLATE_PATH", APP_WEBROOT_PATH."/themes");
define("APP_SPK","NSwxNiwzLDcsMTMsMjQsMTgsMTEsNwDo6UbLe8EqUA3L");
//define("APP_ADMIN_PATH", APP_WEBROOT_PATH."/admin");
define("APP_UTIL_PATH", APP_COM_PATH."/util");
define("APP_I18N_PATH", APP_COM_PATH."/i18n");
//define("APP_WEB_XML",APP_CONFIG_PATH."/../WebRoot/web.xml");
//加载异常处理
require_once(APP_CORE_EXCEPTION_PATH."/AppException.php");         
//加载通用函数
require_once(APP_CORE_UTIL_PATH."/Common.php");
//网站信息配置文件
define("APP_SITE_CONFIG_FILE",APP_DB_PATH."/SiteConfig.php");
//require_once(APP_SITE_CONFIG_FILE);
//数据库配置
define("APP_DB_CONFIG_FILE",APP_DB_PATH."/DbConfig.php");
define("APP_DB_CLASS_FILE",APP_CORE_DATABASE_PATH."/db_mysql.php");
define("APP_DB_FILE",APP_CORE_DATABASE_PATH."/AppDb.php");    
//define("APP_TB_CONFIG_FILE",APP_DB_PATH."/TbConfig.php");
require_once(APP_DB_CONFIG_FILE);
require_once(APP_DB_CLASS_FILE);
require_once(APP_DB_FILE);                                          
//加载Smarty模板文件类
require_once(APP_SMARTY_PATH."/Smarty.class.php");          
//加载Combox类
require_once(APP_CORE_UTIL_PATH."/ComboHelper.php");            
//加载Id生成函数
require_once(APP_CORE_UTIL_PATH."/IdMaker.php");                   
//加载文件操作类
require_once(APP_CORE_UTIL_PATH."/FileUtil.php");
//加载发送邮件类
require_once(APP_CORE_UTIL_PATH."/PHPMailer.php");               
//加载水印
require_once(APP_CORE_UTIL_PATH."/WaterMark.php");
//加载I18N
require_once(APP_CORE_I18N_PATH."/I18n.php");
//加载模板处理文件
require_once(APP_CORE_VO_PATH."/Template.php");
//加载ModelMap处理文件
require_once(APP_CORE_VO_PATH."/ModelMap.php");
//加载ModelAndView处理文件                              
                                                          
require_once(APP_CORE_VO_PATH."/ModelAndView.php");
//加载Action
require_once(APP_CORE_ACTION_PATH."/Action.php");
//加载DAO
require_once(APP_CORE_DAO_PATH."/DAO.php");
//加载VO
require_once(APP_CORE_VO_PATH."/VO.php");

?>