<?php
/**
* 文件处理的基类
* 这个类中有写入、读取文件的操作方法
* @author 我不是稻草人 www.cntaiyn.cn
* @version 1.0
* @copyright 2006-2010
* @package class
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
class FileUtil{
	private $text;
	/**
	 * Article的构造函数 无需传参数
	 */
	function __construct(){
	}
	/**
	 * 函数setText,设置要写入的字符串，saveToFile保存到文件的内容就是这个设置
	 * 返回true
	 * @param string $txt 字符串
	 * @return boolean
	 */
	function setText($txt){
		$this->text=$txt;
		return true;
	}
	/**
	 * 函数saveToFile,把setText设置的字符串写到到文件中
	 * 成功返回true 失败:如果返回r1 则表示文件不存在 r2为文件不可写
	 * @param string $filename 文件名
	 * @return boolean
	 */
	function saveToFile($filename){
		$fileHandle = fopen($filename, "w");
		if($fileHandle){
			if(is_writable($filename)){
				return fwrite($fileHandle, $this->text);
			}else{
				return 'r2';
			}
		}else{
			return 'r1';
		}
		fclose($fileHandle);
	}
	/**
	 * 函数getContent,返回文件的内容
	 * 成功返回内容 失败返回false
	 * @param string $filename 文件名
	 * @return string|boolean
	 */
	function getContent($filename){
		$fp=fopen($filename,'r');
        $content = "";
		if($fp){
			while(!feof($fp)){
				$content.=fgets($fp,4096);
			}
		}else{
			return false;
		}
		fclose($fp);
		return $content;
	}
	/**
	 * 函数UpdateConfig,更新全站配置
	 * 返回 r1为修改的配置数据错误
	 * 返回 r2为修改成功
	 * 返回 r3为写入配置文件失败
	 * @param array $configInfo 配置数据 用数组表示,用$key=>$value来表示列名=>值 如array('title'=>'标题') 表示配置title的值为 标题
	 * @param string $configFileName 配置文件名
	 * @return string
	 */
	function UpdateConfig($configInfo,$configFileName=''){
		//$f=new FClass();
		if(empty($configFileName)){
			$configFileName=WEB_INCLUDE.'config.php';
		}
				 
		$configTxt=$this->getContent($configFileName);
		//替换
		foreach($configInfo as $key=>$value){
			$configTxt=preg_replace("/[$]".$key."\s*\=\s*[\"'].*?[\"'];/is", "\$".$key." = '".$value."';", $configTxt);
			//$configTxt=str_replace("{\$".$key."}",$value,$configTxt);
		}
		$this->setText($configTxt);
		if($this->saveToFile($configFileName)){
			return true;
		}else{
			return false;
		}
	}
    
    /**********************  
    一个简单的目录递归函数  
    第一种实现办法：用dir返回对象  
    ***********************/  
    function tree($directory)    
    {    
        $mydir = dir($directory);    
        echo "<ul>\n";    
        while($file = $mydir->read())   
        {    
            if((is_dir("$directory/$file")) AND ($file!=".") AND ($file!=".."))    
            {   
                echo "<li><font color=\"#ff00cc\"><b>$file</b></font></li>\n";    
                tree("$directory/$file");    
            }    
            else    
            echo "<li>$file</li>\n";    
        }    
        echo "</ul>\n";    
        $mydir->close();    
    }    
    //开始运行   
    //echo "<h2>目录为粉红色</h2><br>\n";    
    //tree("./php");    
    /***********************  
    第二种实现办法：用readdir()函数  
    ************************/  
    function listDir($dir)   
    {   
        if(is_dir($dir))   
        {   
            if ($dh = opendir($dir))    
            {   
                while (($file = readdir($dh)) !== false)   
                {   
                    if((is_dir($dir."/".$file)) && $file!="." && $file!="..")   
                    {   
                        echo "<b><font color='red'>文件名：</font></b>",$file,"<br><hr>";   
                        listDir($dir."/".$file."/");   
                    }   
                    else  
                    {   
                        if($file!="." && $file!="..")   
                        {   
                            echo $file."<br>";   
                        }   
                    }   
                }   
                closedir($dh);   
            }   
        }   
    }
}
?>