<?php
//--------------------------------------------------------------------
//使用范例：
//$databasepath="database.mdb";
//$dbusername="";
//$dbpassword="";
//include_once("class.php");
//$access=new Access($databasepath,$dbusername,$dbpassword);

//--------------------------------------------------------------------
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
    class DbAccess
    {
		 var $databasepath="./PhpSource/Lib/db_website.mdb";
         var $username="";
         var $password="";
		 var $Query_ID = 0;
		 var $constr="";
		 var $link;
		 
         function DbAccess()
         {   
			 if(file_exists("./PhpSource/Lib/db_website.mdb"))$this->databasepath="./PhpSource/Lib/db_website.mdb";
			 if(file_exists("../PhpSource/Lib/db_website.mdb"))$this->databasepath="../PhpSource/Lib/db_website.mdb";
			 if(file_exists("../../PhpSource/Lib/db_website.mdb"))$this->databasepath="../../PhpSource/Lib/db_website.mdb";
         }
        
    function connect()
    {
		
        $this->constr="DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" . realpath($this->databasepath); 
        $this->link=odbc_connect($this->constr,$this->username,$this->password,SQL_CUR_USE_ODBC);
        
        /*if($this->link) 
			echo "恭喜你,数据库连接成功!";
        else 
			echo "数据库连接失败!";*/
		return $this->link;
    }
        
    function query($sql)
    {
		/* 查询语句为空 返回0*/
		if ($sql == "") return 0;
		/* 数据库连接不成功 返回0*/
		if (!$this->connect()) return 0; 
        return $Query_ID = @odbc_exec($this->link,$sql);
    }
    
    function first_array($sql)
    {
        return odbc_fetch_array($this->query($sql));
    }
        
    function fetch_row($query)
    {
        return odbc_fetch_row($query);
    }
        
    function total_num($sql)//取得记录总数
    {
        return odbc_num_rows($this->query($sql));
    }
        
    function close()//关闭数据库连接函数
    {    
        odbc_close($this->link);
    }
            
    function insertInfo($table,$field)//插入记录函数
    {
        $temp=explode(',',$field);
        $ins='';
        for ($i=0;$i<count($temp);$i++)
        {
            $ins.="'".$_POST[$temp[$i]]."',";
        }
        $ins=substr($ins,0,-1);
        $sql="Insert INTO ".$table." (".$field.") VALUES (".$ins.")";
        $this->query($sql);
    }
        
    function getRow($table,$fields,$condition="")//取得一条记录详细信息
    {
        $sql="Select ".$fields." FROM ".$table." ".$condition;
        $query=$this->query($sql);
		$fieldsArray=explode(",",$fields);//分解字段字符串
        if($this->fetch_row($query))
        {
            for ($i=0;$i<count($fieldsArray);$i++)
            {
          		$info[$i]=odbc_result($query,$i+1);
             }
         }
         return $info;
    }
        
       
    function getList($table,$fields,$condition="",$sort="")//取得记录列表
    {
         $sql="Select ".$fields." FROM ".$table." ".$condition." ".$sort;
         $query=$this->query($sql);
         $i=0;
 		 $fieldsArray=explode(",",$fields);//分解字段字符串
         while ($this->fetch_row($query)) 
         {
			 for ($j=0;$j<count($fieldsArray);$j++)
			 {
				$info[$j]=odbc_result($query,$j+1);
			 }    
			 $rdlist[$i]=$info;
			 $i++;
         }
         return $rdlist;
    }
        
    function updateInfo($table,$field,$id,$set)//更新记录
    {
        $sql="Update ".$table." SET ".$set." Where ".$field."=".$id;
        $this->query($sql);
    }
	 function update($sql)//更新记录
    {
        return $this->query($sql);
    }
        
    function deleteInfo($table,$field,$id)//删除记录
    {
         $sql="Delete FROM ".$table." Where ".$field."=".$id;
         $this->query($sql);
    }
	 function delete($sql)//删除记录
    {
         return $this->query($sql);
    }
	function insert($sql)//插入记录
    {
         return $this->query($sql);
    }
        
    function delete1($table,$condition)//删除指定条件的记录
    {
         $sql="Delete FROM ".$table." ".$condition;
         $this->query($sql);
    }
        
    function getcondRecord($table,$condition="")// 取得指定条件的记录数
    {
         $sql="Select COUNT(*) AS num FROM ".$table." ".$condition;
         $query=$this->query($sql);
         $this->fetch_row($query);
         $num=odbc_result($query,1);
         return $num;            
    }
     }
?>