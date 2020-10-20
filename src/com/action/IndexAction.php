<?php
/*
=============================================
* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : IndexAction.php
*  create date: 2013-2-9
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wit521@gmail.com
*  Http       : www.java-php.com
=============================================
*/ 
//error_reporting(0);

import("com.dao.BaseDAO");
    
class  IndexAction extends Action{
	
	function index(){
		$model =new ModelMap();


		return new ModelAndView("index",$model); 
	}
	
    
   

}


?>