<?php
/* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : XmlUtil.php
*  create date: 2010-12-9
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wangyuantao@coscoqd.com
=============================================
* 要获取Title标签的Id属性要分两部走 
* 1. 获取title中所有属性的列表也就是$title->item(0)->attributes 
* 2. 获取title中id的属性，因为其在第一位所以用item(0) 
* 
* 小提示： 
* 若取属性的值可以用item(*)->nodeValue 
* 若取属性的标签可以用item(*)->nodeName 
* 若取属性的类型可以用item(*)->nodeType 
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}

class XmlUtil{
    
    var $file;
    var $xml;
    var $action_mapping;
    
    function XmlUtil(){
        $this->xml = new DOMDocument(); 
    }
    
    function setFile($fileName){
        $this->file = $fileName;
    }
    
    function loadXml(){
        // 加载Xml文件 
        echo "wangyuamtao:".$this->file;
        $this->xml->load($this->file);  
        // 获取所有的post标签 
        $actions = $this->xml->getElementsByTagName("action"); 
        
        foreach($actions as $action){ 
            $name = $action->attributes->item(0)->nodeValue;
            $path = $action->getElementsByTagName("path")->item(0)->nodeValue; 
            $forward = $action->getElementsByTagName("forward")->item(0)->nodeValue;
            $this->action_mapping[$name]=array("action"=>$path,"template"=>$forward);
            
        } 
        //print_r($xml); //输出 XML 
    }
    
    function loadActionMapping(){
        // 加载Xml文件 
        $this->xml->load($this->file);  
        // 获取所有的post标签 
        $actions = $this->xml->getElementsByTagName("action"); 
        foreach($actions as $action){ 
            $name = $action->attributes->item(0)->nodeValue;
            $path = $action->getElementsByTagName("path")->item(0)->nodeValue; 
            $forward = $action->getElementsByTagName("forward")->item(0)->nodeValue;
            $this->action_mapping[$name]=array("action"=>$path,"template"=>$forward);
        } 
    }
    
    function getActionMapping(){
        return  $this->action_mapping;
    }
    
    
}

?>