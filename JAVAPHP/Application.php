<?php
/*
=============================================
* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : Application.php
*  create date: 2010-12-9
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wangyuantao@coscoqd.com
=============================================
*/
define("TCS_APP",true);
define("APP_MAIN_PATH", dirname(__FILE__));
define("APP_CONFIG",APP_MAIN_PATH."/AppConfig.php");
define("APP_V_ID",APP_ID);
        
//import_config();
require_once(APP_CONFIG);

class Application{

    var $module = "";
    var $method = "";
    var $APP_ACTION = "";
    var $APP_TEMPLATE_FILE = "";
    var $action_mapping=array();
    var $APP_NAME = "";
    var $APP_DESCRIPTION = "";
    var $APP_AUTHOR ="";
    var $APP_KEYWORDS="";
    var $templateName="default";
    var $HTTP_REQUEST;
    var $adminFlag=false;
    var $publicHtml = false;
    var $publicHtmlPath = "public/html";
    var $appIndexPath = "";
    var $appActionPath = "";
    var $appLangName = "";

    //实例化
    function Application(){

    }
    //设置是否是后台管理
    function setAdminFlag($adminFlag){
        $this->adminFlag= $adminFlag;
    }
    //设置HTTP_REQUEST
    function setHttpRequestAndParams($request){
        $this->HTTP_REQUEST =  $request;
        //给module和action变量赋值,默认模块

		if($this->adminFlag){
        	$module = isset($this->HTTP_REQUEST["module"])?$this->HTTP_REQUEST["module"]:(isset($this->HTTP_REQUEST["mod"])?$this->HTTP_REQUEST["mod"]:"admin/AdminIndex");
        	$method = isset($this->HTTP_REQUEST["action"])?$this->HTTP_REQUEST["action"]:(isset($this->HTTP_REQUEST["act"])?$this->HTTP_REQUEST["act"]:"userLogin");
		}else{
			$module = isset($this->HTTP_REQUEST["module"])?$this->HTTP_REQUEST["module"]:(isset($this->HTTP_REQUEST["mod"])?$this->HTTP_REQUEST["mod"]:"index");
        	$method = isset($this->HTTP_REQUEST["action"])?$this->HTTP_REQUEST["action"]:(isset($this->HTTP_REQUEST["act"])?$this->HTTP_REQUEST["act"]:"index");
		}

        $this->setModuleAndMethod($module,$method);
		//设置模板名
		$this->setTemplateName(APP_TEMPLATE_NAME);
        //设置国际化I18N
        $this->appLangName = $this->HTTP_REQUEST["lang"]==""?$this->appLangName:$this->HTTP_REQUEST["lang"];
    }
    //设置模板名
    function setTemplateName($templateName){
        $this->templateName = $templateName;
    }
    //赋值模块名
    function setModuleAndMethod($module,$method){
        $this->module= $module;
        $this->method= $method;
    }
    function setPublicHtml($flag=false,$path="public/html"){
        $this->publicHtml = $flag;
        $this->publicHtmlPath = $path;
    }
    //启动应用程序
    function app_run(){
		//判断是否开启Rewrite
        if(APP_REWRITE){
            $_SESSION["rewrite"] = APP_REWRITE;
        }
        //define("PUBLIC_HTML",$this->publicHtmlPath);
        //判断是否支持HTML
        //if($this->publicHtml){
        //    redirect($this->publicHtmlPath);
        //    return ;
        //}
		//设置HTTP_REQUEST
		$this->setHttpRequestAndParams($_REQUEST);
        //初始化Action Mapping文件

        //国际化i18N设置
        if($this->appLangName!=""){
            $_SESSION["APP_LANG_NAME"] = $this->appLangName;
        }

		/*
        if(!is_file($this->APP_ACTION)){
            $error = "【运行错误】：配置文件有误！原因：找不到".$this->module."对应的Action文件!";
            exit($error);
        }
              */
        //初始化Action
        //$pathModuleArr = preg_split("/\//",$this->module);
        $ActionName = $this->getActionName();
		//获取Action文件
        $this->APP_ACTION = $this->getActionPath();
		/*
        //查找配置文件中的ActionName
        $app_ac_arr =  preg_split("/\//",preg_replace("/.php/","",$this->APP_ACTION));
        if(count($app_ac_arr)>0){
            $ActionName = $app_ac_arr[count($app_ac_arr)-1];
        }
        */

        if(!file_exists($this->APP_ACTION)){
            echo("【错误信息】：文件不存在或已被删除！");
            exit;
        }
        if(!class_exists($ActionName)){
            //echo "class不存在";
            //exit;
            include($this->APP_ACTION);
        }

        //if(include($this->APP_ACTION)){
            $actionHandler = new $ActionName();
            //$ActionName =ucwords($this->module)."Action";
            $methodName=$this->method;
            $mav = $actionHandler->$methodName();
            //如果没有mav,返回false
            if(count($mav)==0)return false;
            //$view = $mav->getView();
            //$model = $mav->getModel();
            //$mav = $actionHandler->mav;

            //获取ModelAndView并且解析它
            /*$view = $mav->getView();
			//$view = strripos($view, ".")?$view:$view.".tpl";
            //站点配置文件
            //$site_config=array();
            //print_r($site_config);
            //关于JSON的处理

            if(strtoupper($view)=="JSON"){
                $model = $mav->getJsonModel();
                if(is_object($model)&&array_key_exists("data",$model)){
                    $temp = (object)$model;
                    if(is_object($temp->data)){
                        echo urldecode(json_encode($model->data));
                    }else{
                        echo urldecode(json_encode($model));
                    }
                }else{
                    echo urldecode(json_encode($model));
                }

            }else{
                //获取ModelAndView并且解析它
                $model = $mav->getModel()->data;
                //获取Template文件
               if($this->adminFlag){

                    //$templateFile = APP_WEBROOT_PATH."/".APP_ADMIN_FOLDER.$this->getTemplate($view);
					//$templateFile = APP_WEBROOT_PATH."/".APP_ADMIN_FOLDER."/".$view;
					$templateFile = APP_WEBROOT_PATH."/".APP_ADMIN_FOLDER."/".$view;
               }else{
                    //$templateFile = APP_TEMPLATE_PATH."/".APP_TEMPLATE_NAME."/".$view;
					$templateFile = $view;
               }
                /*
                //初始化模板
                //$tpl = new ParseHTML($templateFile);
				$tpl = new Template();
                //项目配置文件
                include(APP_SITE_CONFIG_FILE);
               // foreach($site_config as $k=>$v){
               //     $tpl->parseVar("site_config.".$k,$v);
               // }
			   $tpl->assign("site_config",$site_config);
                //常量赋值
                if($this->adminFlag){
                    //$tpl->parseVar("title",$this->APP_NAME."后台管理程序");
					$tpl->assign("title",$this->APP_NAME."后台管理程序");
                }else{
                    //$tpl->parseVar("title",$this->APP_NAME);
					$tpl->assign("title",$this->APP_NAME);
                }
                $tpl->assign("author",$this->APP_AUTHOR);
                $tpl->assign("keywords",$this->APP_KEYWORDS);
                $tpl->assign("description",$this->APP_DESCRIPTION);
                if($this->adminFlag){
                    $tpl->assign("template",APP_WEBROOT_PATH."/".APP_ADMIN_FOLDER);
                }else{
                    $tpl->assign("template",APP_TEMPLATE_PATH."/".APP_TEMPLATE_NAME);
                }

                //变量赋值
                if(isset($model)&&$model!=""){
                    foreach($model as $key=>$value){
                        switch(gettype($value)){
                            case "string":
                            $tpl->assign($key,$value);
                            break;
                            case "array":
                            $tpl->assign($key,$value);
                            break;
                            case "object": //phplib模板专用
                            $tpl->assign($key,$value);
                            break;
                        }
                    }
                }


                $tpl->display($templateFile);* /
            }
			*/
            //$tpl = new Template();
            //$tpl->assign("major11",$model);
            //$tpl->display($view);


        //}

  }
    /*
    //初始化Action Mapping文件
    function initActionMapping(){
        $xml = new DOMDocument();
        $xml->load(APP_WEB_XML);
        $this->APP_NAME = $xml->getElementsByTagName("display-name")->item(0)->nodeValue;
        $this->APP_AUTHOR = $xml->getElementsByTagName("author")->item(0)->nodeValue;
        $this->APP_KEYWORDS = $xml->getElementsByTagName("keywords")->item(0)->nodeValue;
        $this->APP_DESCRIPTION = $xml->getElementsByTagName("description")->item(0)->nodeValue;
        $modules = $xml->getElementsByTagName("module");
        foreach($modules as $moduleNode){
            $name = $moduleNode->attributes->item(0)->nodeValue;
            $class = $moduleNode->attributes->item(1)->nodeValue;
            $forwards = $moduleNode->getElementsByTagName("forward");
            $forwardsArr = array();
            foreach($forwards as $forwardNode){
                $forwardName = $forwardNode->attributes->item(0)->nodeValue;
                $forwardPath = $forwardNode->nodeValue;
                $forwardsArr[$forwardName]=$forwardPath;

            }
            $this->action_mapping[$name]=array("class"=>$class,"forwards"=>$forwardsArr);
        }
    }           */
    //获取Action文件
    function getActionName(){
        $_newActionName= $this->module;
        if(isset($this->module)){
            $pathModuleArr = preg_split("/\//",$this->module);
            if(count($pathModuleArr)>0){
                $_newActionName = ucwords($pathModuleArr[count($pathModuleArr)-1]);
            }

        }
        return $_newActionName."Action";
    }
	//获取Action文件
    function getActionPath(){

        $_newActionPath = "";
        if($this->module){
            $pathModuleArr = preg_split("/\//",$this->module);
            if(count($pathModuleArr)>0){
                for($i=0;$i<count($pathModuleArr);$i++){
                    if($i==count($pathModuleArr)-1){
                         $_newActionPath .= ucwords($pathModuleArr[$i]);
                    }else{
                         $_newActionPath .= $pathModuleArr[$i]."/";
                    }
                }
            }else{
                $_newActionPath = $this->module;
            }
        }

		$path = APP_ACTION_PATH."/".$this->appActionPath."/".$_newActionPath."Action.php";

		return $path;
    }
    /*
    //获取Template文件
    function getTemplate($templateIndex){
        return count($this->action_mapping)>0?$this->action_mapping[$this->module]["forwards"][$templateIndex]:"";
    }
    */

    function setIndexPath($path){
        if(substr(APP_PATH,strlen(APP_PATH)-1)=="/"){
             //$path = preg_replace("/".substr(APP_PATH,0,strlen(APP_PATH)-1)."/","",$path);
             $posIndex =  strlen(APP_PATH)-1;
        }else{
             //$path = preg_replace("/".APP_PATH."/","",$path);
             $posIndex =  strlen(APP_PATH);
        }
        $path = substr($path,$posIndex+1);
        if($path!=""){
             $arr = preg_split("/\\\/",$path);    //此处需要使用三个反斜杠
             //echo $path;
             for($i=0;$i<count($arr);$i++){
                 $this->appIndexPath .= "../";
                 if($i==0){
                      $this->appActionPath .= "".$arr[$i];
                 }else{
                      $this->appActionPath .= "/".$arr[$i];
                 }

             }

        }
        //define("APP_INDEX_PATH",$this->appIndexPath);
        $GLOBALS['APP_INDEX_PATH'] = $this->appIndexPath;
        //define("APP_INDEX_PATH",$this->appIndexPath."|AAA");
    }

    function getIndexPath($path){
        return $this->appIndexPath;
    }

}
?>