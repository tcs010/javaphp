<?php
/* Copyright (c) 2011-2013 Create By WangYuantao
=============================================
*  Function   : Action.php
*  create date: 2011-10-13 13:09:38
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wit521@gmail.com
*  Http       : www.java-php.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}

class Action {
    
    var $templateFile ="";
    var $JSON_VIEW = "json";
    var $ADMIN_ID ="1";
    var $ADMIN_NAME = "admin"; 
	var $tpl = NULL;
    var $_TITLE_ = "后台管理";
    var $SUCCESS = "success";
    var $FAILURE = "failure";
    
	public function __construct(){
		if(!class_exists("Template")){
			include(SYS_CLS_TPL);
		}
		$this->tpl = new Template();
		$GLOBALS["sys"]["tpl"] = $this->tpl;
        //$this->initMembersInfo();
        _action_observer();
	}
    
    //调用不存在的方法时调用
    function __call($name,$arguments) { 
        //print("调用的方法[".$this->className."::".$name."]不存在!"); 
        print("【错误信息】：功能调用错误，不存在!"); 
    }
    
    public function setTemplateFile($templateFile){
        $this->templateFile = $templateFile;
    }
	
    public function getSession($index){
         return $_SESSION[$index];
    }
    
	public function getSessionMemberId(){
         return $_SESSION["TCS_MEMBER_ID"];
    }
    
    public function getSessionMajorId(){
         return $_SESSION["TCS_MAJOR_ID"];
    }
	
    //表单填充VO
    public function formFillVo($vo){
         foreach($_REQUEST as $key=>$value){
             foreach($vo as $k=>$v){
                 if($key==$k){
                     $vo->$key=isset($_REQUEST[$key])?$_REQUEST[$key]:"";
                     break;
                 }
             }
         }
         return $vo;
    }
    //获取$_REQUEST参数
    public function getHtmlParameter($param){
        return isset($_REQUEST[$param])?htmlspecialchars($_REQUEST[$param],"ENT_QUOTES"):""; 
    }
    //获取$_REQUEST参数
    public function getSecurityParameter($param){
        return isset($_REQUEST[$param])?strip_tags($_REQUEST[$param],"ENT_QUOTES"):""; 
    }
    //获取$_REQUEST参数
    public function getParameter($param,$htmlspecialchars=false){
        if($htmlspecialchars){
            return isset($_REQUEST[$param])?strip_tags($_REQUEST[$param],"ENT_QUOTES"):""; 
        }else{
            return isset($_REQUEST[$param])?$_REQUEST[$param]:"";
        }
    }
    //获取$_REQUEST参数
    public function getStringParameter($param){
        return isset($_REQUEST[$param])?$_REQUEST[$param]:""; 
    }
    //获取$_REQUEST参数
    public function getIntegerParameter($param){
        return (isset($_REQUEST[$param])&&$_REQUEST[$param]!="")?$_REQUEST[$param]:"0"; 
    }
    //获取$_REQUEST参数
    public function getFloatParameter($param){
        return isset($_REQUEST[$param])?$_REQUEST[$param]:"0.0"; 
    }
    //获取$_REQUEST参数
    public function getBooleanParameter($param){
        return isset($_REQUEST[$param])?$_REQUEST[$param]:false; 
    }
    
    
    function adminLogin(){   
        $model =new ModelMap();     
        $_SESSION["ADMIN_ID"]=$this->ADMIN_ID;
        $_SESSION["ADMIN_USERNAME"]=$this->ADMIN_NAME;
        $model->put("username",$this->ADMIN_NAME);
        return new ModelAndView("admin/index",$model);  
    }
    //格式化产品列表
    function formatList($rows,$vo,$sublen=22,$showFlag=false,$isIndex=false){
        $result = array();
        $num=0;
        if($isIndex)$num=1;
        for($i=$num;$i<count($rows);$i++){
            $vo = $rows[$i];
            if(strlen($vo->getTitle())>$sublen){
                $vo->setTitle($this->substr_cut($vo->getTitle(),$sublen)); 
                //$vo->setTitle(substr($vo->getTitle(),0,$sublen)."...");  
            }
            if($vo->getIsNew()>0&&$showFlag){
                $vo->setIsNew("<img src='./WebRoot/themes/".APP_TEMPLATE_NAME."/images/icon_new.gif' width='20' height='17' />");
            }else{
                $vo->setIsNew("");
            }
            array_push($result,$vo);
        }
        return $result; 
    }
    
    //格式化产品列表
    function formatListForIndex($rows,$vo,$sublen=22){
        $result = array();
        $num=1;
        for($i=$num;$i<count($rows);$i++){
            $vo = $rows[$i];
            if(strlen($vo->getTitle())>$sublen){
                $vo->setTitle($this->substr_cut($vo->getTitle(),$sublen,true)); 
            }
            array_push($result,$vo);
        }
        return $result; 
    }
    
    function substr_cut($str_cut,$length = 22,$flag=false){ 
        if (strlen($str_cut) >$length){
        for($i=0; $i < $length; $i++)
            if (ord($str_cut[$i]) > 128)$i++;
            if(!$flag){
                $str_cut = substr($str_cut,0,$i)."...";
            } else{
                $str_cut = substr($str_cut,0,$i);    
            }
        }
        return $str_cut;
    }

    function navClass($model,$num=1){
        for($i=1;$i<=9;$i++){
            if($i==$num){
                $model->put("nav-class-".$i,"nav-selected");
            }else{
                $model->put("nav-class-".$i,"nav-normal");
            }
        }
        return $model;
    }
    
    function loginClass($model){
        if($this->checkAuthor()){
            $model->put("nologin","display:none;");
            $model->put("logined","display:block;");
            $model->put("username",$_SESSION["MEMBER_USERNAME"]);
        }else{
            $model->put("nologin","display:block;");
            $model->put("logined","display:none;");
        }
        return $model;
    }
    
    function pdf2swf($filePath){

        $pdfFile = APP_PATH."\\".str_replace("/","\\",str_replace("./","\\",$filePath));
        $curYmdhis = date("YmdHis");
        $swfFile = APP_PATH."\\pdf\\".$curYmdhis.".swf";
        @unlink($swfFile);   
        //使用pdf2swf转换命令   D:\SWFTools\pdf2swf.exe
        $command= "D:/SWFTools/pdf2swf.exe  -t \"".$pdfFile."\" -o  \"".$swfFile."\" -s flashversion=9 ";  
        //$command= "D:/SWFTools/pdf2swf.exe  -t \"".$pdfFile."\" -o  \"".$swfFile."\" -T 9 ";    
        //创建shell对象   
        $WshShell = new COM("WScript.Shell");   
        //执行cmd命令   
        $oExec = $WshShell->Run($command, 0, true); 
        return "./pdf/".$curYmdhis.".swf";
    }
    
    function formatVideoPath($path){
        return str_replace("./WebRoot/","../../../",$path);
    }
    
    function hotLabel($model){
        import("com.dao.SiteInfoDAO"); 
        import("com.vo.SiteInfoVO"); 
        $siteInfoDAO = new SiteInfoDAO;
        $siteInfoVO = new SiteInfoVO;
        $siteInfoVO = $siteInfoDAO->getById(1);
        $hotlabelStr = $siteInfoVO->getSiteLabel();
        $labelArr = preg_split("/\|/",$hotlabelStr);
        $result="";
        for($i=0;$i<count($labelArr)&&$$i<15;$i++){
            $result .="<li><a href=\"./index.php?module=index&action=search&keywords=".$labelArr[$i]."\">".$labelArr[$i]."</a></li>";
        }
        $model->put("hotLabel",$result);
        
        return $model;
    }
    
    function goToUrl($uri,$msg="") { 
        //session_destroy();
        if(isset($msg)&&$msg!=""){
            echo "<script>alert('".$msg."');location.href='".$uri."'</script>";  
        }else{
            echo "<script>location.href='".$uri."'</script>"; 
        }
    }
    function redirectGoBack($msg){
         echo "<script>alert('".$msg."');history.go(-1);</script>";  
    }
    function redirect($uri,$msg="") { 
        //session_destroy();
        if(isset($msg)&&$msg!=""){
            echo "<script>alert('".$msg."');top.location.href='".$uri."'</script>";  
        }else{
            echo "<script>location.href='".$uri."'</script>"; 
        }
        
    }

    //创建html静态文件
    //$this->makeHtml("news/show","news/71");
     function makeHtml($templatePath="", $htmlPath="",$htmlLinks=array(),$suffix=".shtml"){
        $tpl = new Template();
        $tpl->makeHtml($templatePath,$htmlPath,$htmlLinks,$suffix);
        unset($tpl);
    }
    
    //随机生成ID
    function generateId(){
        return date("Ymdhis").floor(microtime()*1000+1000);
    }
    //根据HTML模板创建文件
    function createHTML($module="",$filename="",$template="",$arr,$ymFolder=false){
        $fileUtil = new FileUtil();
        $content = $fileUtil->getContent(APP_TEMPLATE_PATH."/".APP_TEMPLATE_NAME."/".$template);
        //文章内容
        foreach($arr as $k=>$v){
            foreach($v as $k1=>$v1){
                //处理list
                $reg = "/<!--\s+BEGIN $k1\s+-->(.*)\n\s*<!--\s+END $k1\s+-->/sm";
                $content = preg_replace($reg, $v1, $content);
                //处理变量
                $content = preg_replace("/{".$k1."}/i",$v1,$content);
            }
        }
        //网站基础信息
        include(APP_SITE_CONFIG_FILE);
        if(is_array($site_config)){
            foreach($site_config as $k2=>$v2){
                $content = preg_replace("/{site.".$k2."}/i",$v2,$content);
            }
        }

        $fileUtil->setText($content);
        
        $path = PUBLIC_HTML;
        if($module=="/"||$module=="./"||$module=="."){
            $fileUtil->saveToFile($path."/".$filename.".html");
        }else{
            $module = (substr($module,0,1)=="/")?substr($module,1):$module;
            $module = (substr($module,strlen($module)-1)=="/")?substr($module,0,strlen($module)-1):$module;
            $pathArr = preg_split("/\//",$module);
            
            for($i=0;$i<count($pathArr);$i++){
                $path = $path."/".$pathArr[$i];
                if(!is_dir($path)){mkdir($path,0777);}
            }

            if($ymFolder){
                $path = $path."/".date("Y-m");
                if(!is_dir($path)){mkdir($path,0777);}
            }
            $fileUtil->saveToFile($path."/".$filename.".html");
        }
    }
    
    function updateSiteInfo(){
        $result = "<?php
        /* Copyright (c) 2011-2013 Create By WangYuantao
        =============================================
        *  Function   : SiteConfig.php
        *  create date: ".date("Y-m-d H:i:s")."
        *  create By  : Wang Yuantao
        *  QQ         : 23479184
        *  Email      : wit521@gmail.com
        *  Http       : www.java-php.com
        =============================================
        */

        \$site_config = array(

             \"domain\" => \"".$_POST["domain"]."\",
             \"name\" => \"".$_POST["name"]."\",
             \"keywords\" => \"".$_POST["keywords"]."\",
             \"descripts\" => \"".$_POST["descripts"]."\",
             \"owner\" => \"".$_POST["owner"]."\",
             \"phone\" => \"".$_POST["phone"]."\",
             \"mobile\" => \"".$_POST["mobile"]."\",
             \"fax\" => \"".$_POST["fax"]."\",
             \"email\" => \"".$_POST["email"]."\",
             \"msn\" => \"".$_POST["msn"]."\",
             \"address\" => \"".$_POST["address"]."\",
             \"zip\" => \"".$_POST["zip"]."\",
             \"icp\" => \"".$_POST["icp"]."\",
             //bannner
             \"banner1\" => \"".$_POST["banner1"]."\", 
             \"banner1Url\" => \"".$_POST["banner1Url"]."\",
             \"banner2\" => \"".$_POST["banner2"]."\",
             \"banner2Url\" => \"".$_POST["banner2Url"]."\",
             \"banner3\" => \"".$_POST["banner3"]."\",
             \"banner3Url\" => \"".$_POST["banner3Url"]."\",
             \"banner4\" => \"".$_POST["banner4"]."\",
             \"banner4Url\" => \"".$_POST["banner4Url"]."\",
             \"banner5\" => \"".$_POST["banner5"]."\",
             \"banner5Url\" => \"".$_POST["banner5Url"]."\"

        );";
        $fileUtil = new FileUtil();
        $fileUtil->setText($result);
        $fileUtil->saveToFile(APP_DB_PATH."/SiteConfig.php");
        
        
    }
    //获取论坛id
    function getBbsMembersId(){
        if(!empty($_COOKIE['Example_auth'])) {
            list($Example_uid, $Example_username) = explode("\t", uc_authcode($_COOKIE['Example_auth'], 'DECODE'));
        } else {
            $Example_uid = $Example_username = '';
        }

        return $Example_uid;
    }
    //获取论坛id
    function getBbsMembersUsername(){
        if(!empty($_COOKIE['Example_auth'])) {
            list($Example_uid, $Example_username) = explode("\t", uc_authcode($_COOKIE['Example_auth'], 'DECODE'));
        } else {
            $Example_uid = $Example_username = '';
        }

        return $Example_username;
    }
    /*
    //初始化会员信息
    function initMembersInfo(){
        if(!empty($_COOKIE['Example_auth'])) {
            list($Example_uid, $Example_username) = explode("\t", uc_authcode($_COOKIE['Example_auth'], 'DECODE'));
        } else {
            $Example_uid = $Example_username = '';
        }
        
        if(isset($Example_uid)&&$Example_uid!=''){
            //include("./src/com/dao/MembersDAO.php");
            //$dao = new MembersDAO();
            //$dao->debug = true;
            $member = $dao->getByUsernameAndID($Example_username,$Example_uid);
            if(count($member)>0){
                $_SESSION["TCS_MEMBER_ID"]=$member[0]["ID"];
                $_SESSION["TCS_MEMBER_USERNAME"]=$member[0]["USERNAME"];
                $_SESSION["TCS_MEMBER_MAJOR_ID"]=$member[0]["MAJOR_ID"];
                $_SESSION["TCS_MEMBER_MAJOR_NAME"]=$member[0]["MAJOR_NAME"];
                $_SESSION["TCS_MEMBER_LEVEL_ID"]=$member[0]["LEVEL_ID"];
                $_SESSION["TCS_MEMBER_LEVEL_NAME"]=$member[0]["LEVEL_NAME"];
                $_SESSION["TCS_MEMBER_PIC"]=$member[0]["PIC"];
                
            } 
        }


        return $Example_uid;
    }
    */

}
?>