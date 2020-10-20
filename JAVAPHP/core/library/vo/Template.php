<?php
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
class Template extends Smarty{
	
	var $db;
	var $is_html = false;//是否启生成用HTML静态页
	var $tpl_ext = ".html";//模板文件后缀
	
	public function Template() {
		parent::__construct();
		
		$this->setTemplateDir($GLOBALS['APP_INDEX_PATH']."WebRoot/themes/".APP_TEMPLATE_NAME."");
		$this->setCompileDir($GLOBALS['APP_INDEX_PATH']."compile/");
		$this->setConfigDir($GLOBALS['APP_INDEX_PATH']."configs/");
		$this->setCacheDir($GLOBALS['APP_INDEX_PATH']."cache/");
		$this->addPluginsDir($GLOBALS['APP_INDEX_PATH']."plugins/");
        
        $GLOBALS["sys"]["tpl"] = $this;
		
		//$this->cache_lifetime =   60 * 60 * 24;           //设置缓存时间
        //$this->caching        = false;            //这里是调试时设为false,发布时请使用true

		$this->left_delimiter = "<{"; 
		$this->right_delimiter = "}>";
		
		//$this->debugging = false;
		/*
		$db = new AppDb();
		$db->Host     = $db_config["host"];
      	$db->Database = $db_config["database"];
     	$db->User     = $db_config["username"];
      	$db->Password = $db_config["password"];
      	$db->Dbencode = $db_config["encode"];
		$this->db = $db;
		*/
	}
	/**
        * 生成静态页
        * @param string $tplName
        * @param string $typeName
        * @param array $processlinks convert to static link url with preg
        *        such as:array(
        *                   0=>array("original"=>"'|\.\/index\.php\?mod\=news\&act\=show\&id=(\d+)|'",
        *                            "target":'/public/news/$1.shtml'),
        *                   1=>array("original"=>"'|\.\/index\.php\?mod\=news\&act\=show\&id=(\d+)|'",
        *                            "target":'/public/news/$1.shtml'),
        *                   );
        * @param string $suffix
        * 
	*/
	 public function makeHtml($templatePath="", $htmlPath="",$htmlLinks=array(),$suffix=".shtml"){
         //模板文件路径
		 $templatePath = "themes/".APP_TEMPLATE_NAME."/".$templatePath.$this->tpl_ext;
         $old_htmlPath = "themes/".APP_TEMPLATE_NAME."/".$htmlPath.$suffix;
         //目标html文件路径
         $old_htmlPath = $htmlPath;
         //$htmlPath = "public/".$old_htmlPath;
         $htmlPath = "public";
         $htmlPathArr = preg_split("/\//",$old_htmlPath);
         $tplName = ""; 
         for($i=0;$i<count($htmlPathArr);$i++){
             if($i==count(count($htmlPathArr)-1)){
                  $htmlPath.= "/".(strpos($htmlPathArr[$i],$suffix)>0?$htmlPathArr[$i]:$htmlPathArr[$i].$this->tpl_ext);
             }else{
                 $htmlPath.="/".$htmlPathArr[$i];
                 if(!is_dir($htmlPath)){
                    @mkdir($htmlPath, 0777);
                    //@fclose(fopen($htmlPath.'/index.htm', 'w'));
                 } 
             }
             echo $htmlPath;
             
         }
         
         unset($i);
         unset($htmlPathArr);
		 $htmlContext = $this->fetch($templatePath); 
         $htmlContext = preg_replace("|.\/WebRoot\/|","/WebRoot/",$htmlContext);
         if(count($htmlLinks)>0){
             for($j=0;$j<count($htmlLinks);$j++){
                 //$htmlContext = preg_replace($processLinks[$j]["original"],$processLinks[$j]["target"],$htmlContext);
                 $htmlContext = preg_replace($htmlLinks[$j]["old"],strpos($htmlLinks[$j]["new"],$suffix)?$htmlLinks[$j]["new"]:$htmlLinks[$j]["new"].$suffix,$htmlContext);
             }
             //$htmlContext = preg_replace('|\.\/index\.php\?mod\=news\&act\=show\&id=(\d+)|','/public/news/$1.shtml',$htmlContext);
         }//else{
          //   $htmlContext = preg_replace('|\.\/index\.php\?mod\=news\&act\=show\&id=(\d+)|','/public/news/$1.shtml',$htmlContext);
         //}
         //$htmlContext = preg_replace('|\.\/index\.php\?mod\=news\&act\=show\&id=(\d+)|','/public/news/$1.shtml',$htmlContext);
		 if ($fp = fopen($htmlPath , "w")){
				if (fwrite($fp, $htmlContext)){
						fclose($fp);
				}else{
						fclose($fp);
						die("对不起,无法写入指定文件: " . $htmlPath . ".shtml");
				}       
		 }else{
				die("对不起,无法打开指定文件: " . $htmlPath . ".shtml");
		 }                
	 }
	 
	 /**
	  * 析构函数
	  */
	 //public function __destruct()
	 //{
	//		unset($this->conn);
	 //}
               
	 public function display($page=NULL, $cache_id = NULL, $compile_id = NULL, $parent = NULL){
		if($this->is_html){
			$page = str_replace($this->tpl_ext,"",$page);
			$this->makeHtml($page,$page);
		}
        // register the outputfilter
        //$this->loadFilter('output','rewrite_link'); 
        //$this->autoload_filters = array('output'=>array('rewrite_link'));
        //$this->autoload_filters['output'] = array();  #取消调入的组件过滤器 

        
       // $this->loadPlugin("smarty_outputfilter_rewrite_link");
        //$this->load_filter('output', 'rewrite_link');
        $tempArr = $this->getTemplateDir();
		parent::display("".$page.$this->tpl_ext, $cache_id, $compile_id, $parent);
	}

	function copyFolder($src,$dst) {  // 原目录，复制到的目录    
        $dir = opendir($src);    
        @mkdir($dst);    
        while(false !== ( $file = readdir($dir)) ) {        
            if (( $file != '.' ) && ( $file != '..' )) {            
                if ( is_dir($src . '/' . $file) ) {                
                    copyFolder($src . '/' . $file,$dst . '/' . $file);            
                }            
                else {                
                    copy($src . '/' . $file,$dst . '/' . $file);            
                }        
            }    
        }    
        closedir($dir);
    }
    
    public function modelAndView($page,$model=null){
        //$GLOBALS["sys"]["tpl"] = $tpl;
        $tpl = $GLOBALS["sys"]["tpl"];
        $arrTpl = $tpl->getTemplateDir();
        //return new ModelAndView(substr($arrTpl[0],0,strlen($arrTpl[0])-1)."/".$page,$model);
        return new ModelAndView($page,$model);
    }

}
?>