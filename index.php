<?php
/* Copyright (c) 2011-2013 Create By WangYuantao
=============================================
*  Function   : index.php
*  create date: 2011-10-13 13:09:38
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wit521@gmail.com
*  Http       : www.java-php.com
=============================================
*/
	session_start();                      
    error_reporting(0);
    error_reporting(E_ALL^(E_STRICT | E_NOTICE | E_WARNING));
    header('Content-Type: text/html; charset=utf-8'); 
    require_once("./config.php");
    //项目初始化
    $App = new Application();         
    //启动项目
    $App->app_run();


  
?>