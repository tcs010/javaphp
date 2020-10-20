<?php
/* Copyright (c) 2011-2013 Create By WangYuantao
=============================================
*  Function   : SiteConfig.php
*  create date: 2011-10-13 13:09:38
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wit521@gmail.com
*  Http       : www.java-php.com
=============================================
*/
    //header('Content-Type: text/html; charset=utf-8');
    //define("TCS_APP",true);
    //定义项目路径
     
    define("APP_PATH", dirname(__FILE__)."");

    //定义FPHP框架路径
    define("JAVAPHP_PATH", APP_PATH."/JAVAPHP");

    //模板文件名
    define("APP_TEMPLATE_NAME", "default");

    //地址Rewrite功能
    define("APP_REWRITE", false);

    //国际化I18N
    define("APP_I18N_NAME", "zh_CN");     //定义默认语言及Sections
    define("APP_I18N_SECTIONS","common,login-box,news-list"); //定义configs目录下的语言文件conf中Sections名列表

    //设置默认时间区域
    date_default_timezone_set ('PRC');

    //设置是否开启日志功能
    define("APP_LOG",false);
    
    //图片水印
    define("APP_WATERMARK",0); //0-不应用 1-图片 2-文字
    define("APP_WATERMARK_TEXT","www.qdywmj.cn");
    define("APP_WATERMARK_IMAGE","images/watermark.png");

    //设置网站唯一key
    define("APP_ID","6D45DD2384F2DB77629F,D062D0464F76667B3A09");

    //定义全局变量
    Global $APP_INDEX_PATH;
                           
    //加载主应用程序
    include(APP_PATH."/JAVAPHP/Application.php");

?>