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
 class ModelMap {   
      
    public $data = array();   
       
    public function put($key,$value)   
    {   
        $this->data[$key] = $value;   
    } 
    
    public function get($key)   
    {   
        return $this->data[$key];   
    }  
    
    public function size()   
    {   
        return count($this->data);   
    }
    
    public function getKeys()   
    {   
        return array_keys($this->data);   
    }   
       
    public function remove($key)   
    {   
        if(isset($this->data[$key]))   
        {   
            $value = $this->data[$key];   
            unset($this->data[$key]);   
            return $value;   
        }   
        return null;   
    }   
       
    public function clean()   
    {   
        foreach($this->getKeys() as $key)   
        {   
            $this->remove($key);   
        }   
    }               
}
?>