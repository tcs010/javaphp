<?php
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
class ModelAndView {

    public $view="json";
    public $model;
	public $adminFlag=false;
	public $isHtml=false;

    function ModelAndView($view,$model=null,$isHtml=false){
        //给View赋值
        $this->view = $view;
		if($model!=""&&isset($model)&&$model!=null){
			$this->model =  $model;
		}else{
			$this->model =new ModelMap();
		}
        if(strtolower($view)=="json"){
			//$this->model =  $model;
            $modelData = array();
			if(is_object($model)&&array_key_exists("data",$model)){
				$temp = (object)$model;
				if(is_object($temp->data)){
                    $modelData = $temp->data; 
				}else{
                    $modelData = $model;
				}
                //处理数据函数
                $modelData = $this->processModelData($modelData);  
                //不对中文字符进行处理
                echo urldecode($this->json_encode_no_zh($modelData));
			}else{
				echo urldecode($this->json_encode_no_zh($model));
			}
        }else{
            //处理I18N国际化
            //if($model!=""&&isset($model)&&is_object($model)){
            //    I18n($model);
            //}

            //echo "dddddd";

		    $this->isHtml = $isHtml; //是否启用静态页生成功能

			//初始化模板
		   $tpl = $GLOBALS["sys"]["tpl"];
           //$tpl = new Template();
		   $tpl->is_html=$this->isHtml;//是否启用静态页
		   //$tpl->assign("site_config",$site_config);
			//常量赋值
			//if($this->adminFlag){
				//$tpl->parseVar("title",$this->APP_NAME."后台管理程序");
				//$tpl->assign("title",$this->APP_NAME."后台管理程序");
			//}else{
				//$tpl->parseVar("title",$this->APP_NAME);
				//$tpl->assign("title",$this->APP_NAME);
			//}
			//$tpl->assign("author",$this->APP_AUTHOR);
			//$tpl->assign("keywords",$this->APP_KEYWORDS);
			//$tpl->assign("description",$this->APP_DESCRIPTION);

			//if($this->adminFlag){
			//	$tpl->assign("template",$GLOBALS['APP_INDEX_PATH'].APP_WEBROOT_PATH."/".APP_ADMIN_FOLDER);
			//}else{
			$tpl->assign("template",$GLOBALS['APP_INDEX_PATH'].APP_TEMPLATE_PATH."/".APP_TEMPLATE_NAME);

			//}

            //加载I18N
            //$tpl->config_load('zh_CN.conf');
            //$lang为通过cookie或session获得的页面语言值

            //echo "ddddddddddd= ".$_SESSION["APP_LANG_NAME"]."<br>";
            //$compiler =new Smarty_Internal_CompileBase();
            //$_attr = $tpl->getAttributes($tpl, $args);

            // save posible attributes
            //$conf_file = $_attr['file'];
            if (isset($_attr['section'])) {
                $section = 'setup';
            } else {
                $section = 'null';
            }
            $section = preg_split("/,/",APP_I18N_SECTIONS);

            $conf_arr = array();
            switch ($_SESSION["APP_LANG_NAME"]) {
                case 'zh_CN' :
                    //$tpl->config_load('zh_CN.conf');
                    $conf_file = "lang/".$_SESSION["APP_LANG_NAME"].".conf";
                    $_config = new Smarty_Internal_Config($conf_file, $tpl->smarty, $tpl);
                    $_config->loadConfigVars($section, 'global');
                    $conf_arr = $_config->data->config_vars;
                    //print_r($_config->data->config_vars);
                    break;
                case 'en_US' :
                    //$tpl->config_load('en_US.conf');
                    $conf_file = "lang/".$_SESSION["APP_LANG_NAME"].".conf";
                    $_config = new Smarty_Internal_Config($conf_file, $tpl->smarty, $tpl);
                    $_config->loadConfigVars($section, 'global');
                    $conf_arr = $_config->data->config_vars;
                    //$scope_ptr = $_config->data;
                    //print_r($_config);
                    break;
                default:
                    //$tpl->config_load( APP_I18N_NAME.".conf" );
                    $conf_file = "lang/".APP_I18N_NAME.".conf";
                    $_config = new Smarty_Internal_Config($conf_file, $tpl->smarty, $tpl);
                    $_config->loadConfigVars($section, 'global');
                    $conf_arr = $_config->data->config_vars;
                    //print_r($_config);
                    break;
            }

            foreach($conf_arr as $key=>$val){
                //echo $key."=".$val."<br>";
                //$tpl->assign("lang_".$key,$val);
                $_SESSION["".$key] = $val;
            }

            //配置文件
            include(APP_SITE_CONFIG_FILE);
            foreach($site_config as $key=>$value){
                 $tpl->assign($key,$site_config[$key]);
            }

			//变量赋值
			if(isset($model)&&$this->model!=""){
				foreach($this->model as $key=>$value){
                    //print_r($value);
                    //print_r($value."|".gettype($value));
					switch(gettype($value)){
						case "string":
						$tpl->assign($key,$value);
						break;
						case "array":
                        //print_r($value);
                            foreach($value as $key1=>$value1){
                                //echo "key:".$key1." , value1:".$value1."<br />";
                                if(gettype($value1)!="array"){
                                   $tpl->assign($key1,$value1);
                                }else{
                                    //foreach($value as $key2=>$value2){
                                    //    if(gettype($value2)!="array"){
                                            $tpl->assign($key1,$value1);
                                    //    }
                                   // }
                                }

                            }
                            //echo "key: ".$key;
                            //$this->assignArray($key,$tpl,$value);
						//$tpl->assign($key,$value);
						break;
						case "object": //phplib模板专用
						$tpl->assign($key,$value);
						break;
					}
				}
                //unset($this->model);
			}

            //是否启用ReWrite功能
            if(APP_REWRITE){
                $tpl->loadFilter('output','rewrite');
            }
            //渲染页面
            $tpl->display($this->view);


		}
    }

    function json_encode_no_zh($arr) {
        $str = str_replace ( "\\/", "/", json_encode ( $arr ) );
        //$search = "#\\\u([0-9a-f]+)#ie";
        $search = "#\\\u([0-9a-f][0-9a-f][0-9a-f][0-9a-f])#ie";
     
        if (strpos ( strtoupper(PHP_OS), 'WIN' ) === false) {
            $replace = "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))";//LINUX
        } else {
            $replace = "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))";//WINDOWS
        }
     
        return preg_replace ( $search, $replace, $str );
    }
    
    function processModelData($modelData){
        /*if(count($modelData)==0)return array();
        foreach($_arr as $_key=>$_value){
            if(gettype($_value)!="array"){
               $_tpl->assign($_new_key,$_value);
            }else{
                $this->assignArray($_new_key,$_tpl,$_value);
            }
        }*/
        return $modelData;
    }

    function assignArray($_p_key,$_tpl,$_arr){
        //print_r($_arr) ;
        if($_p_key!="data")return false;
        foreach($_arr as $_key=>$_value){
            //echo "_p_key: ".$_p_key." |key:".$_key." , value1:".$_value."<br />";
            /*if($p_key=="data"){
                $_new_key = $_key;
            }else{
                $_new_key = $_p_key.".".$_key;
            }
            */
            $_new_key = $_key;
            if(gettype($_value)!="array"){
               $_tpl->assign($_new_key,$_value);
            }else{
                $this->assignArray($_new_key,$_tpl,$_value);
            }
        }
    }

    function getView(){
        return $this->view;
    }

    function getModel(){
        return $this->model;
    }
}
?>