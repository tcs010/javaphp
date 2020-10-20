<?php
/*
=============================================
* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : ModelMap.php
*  create date: 2010-12-9
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wangyuantao@coscoqd.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
 class ComboHelper {   
     var $list = array();
     var $defaultValue = "";
     var $keyValue = "value";
     var $keyText ="text";
     var $selected = "selected";
    
    function ComboHelper($keyValue="value",$keyText="text"){
        $this->keyValue = $keyValue;
        $this->keyText = $keyText;
    }
      
    function combo($list,$defaultValue="",$key="CODE",$text="NAME",$initValue="0",$initText="===请选择==="){
        if(count($list)<=0) $list = $this->list;
        $result = array();
        $temp = array();
        $temp[$this->keyValue] = $initValue;
        $temp[$this->keyText] = $initText;
        if($defaultValue==$initValue){
            $temp[$this->selected] = "true";
        }
        array_push($result,$temp);

        for($i=0;$i<count($list);$i++){
            unset($temp);
            foreach($list[$i] as $k => $v){
                if(strtolower($k)==strtolower($key)){
                    $temp[$this->keyValue]= $v;
                    if($v==$defaultValue){
                        $temp[$this->selected]=  "true";  
                    }
                }else if(strtolower($k)==strtolower($text)){
                    $temp[$this->keyText]= $v;
                }
                
            }
            array_push($result,$temp);
        }
        return $result;
    }
    
} 
?>