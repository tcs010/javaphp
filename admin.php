<?php
/*
=============================================
* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : index.php
*  create date: 2010-12-9
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wangyuantao@coscoqd.com
=============================================
*/ 
	session_start();
    error_reporting(0);
    error_reporting(E_ALL^(E_STRICT | E_NOTICE));
    header('Content-Type: text/html; charset=utf-8');
    require_once("./config.php"); 
    //初始化
    $App = new Application();
    $App->setAdminFlag(true);  
    $App->app_run();  
    
?>