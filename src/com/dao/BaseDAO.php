<?php
/* Copyright (c) 2013-2016 Create By WangYuantao
=============================================
*  Function   : BaseDAO.php
*  create date: 2013-01-09 16:01:44
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wit521@gmail.com
*  Http       : www.java-php.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}

class BaseDAO extends DAO {
    
    //调试
    var $debug = false;
    
    function __construct(){
        parent::__construct();
        //$this->className = __CLASS__;
        //$this->methodName = __FUNCTION__;
    }
    
    function setClassName($_className){
        $this->className = $_className;
    }
     
    
    //获取全部
    function getList(){
        $this->methodName = __METHOD__;
        return $this->selectList();
    }

    //获取一条记录
    function getById($id){
        $this->methodName = __METHOD__;
        return $this->selectByPk($id);
    }
    
    //获取一条记录
    function getByPk($pk){
        $this->methodName = __METHOD__;
        return $this->selectByPk($pk);
    }
    
    //添加一条记录
    function insertRow($row){
        $this->methodName = __METHOD__;
        $this->execInsert($this->tableName,
                        array($row));
    }
    
    //获取最后插入的ID
    function lastInsertId(){
        $this->methodName = __METHOD__;
        $sql = "select LAST_INSERT_ID() num ";
        $rows = $dao->execSql($sql);
        if(count($rows)>0){
            return $rows[0]["num"];
        }
        return 0;
    }
    
    //编辑一条记录
    function updateRow($row,$id){
       $this->methodName = __METHOD__; 
       $this->execUpdate($this->tableName,
                        $row,
                        array($this->primaryKey=>$id));
    }

    //删除一条记录
    function deleteRow($id){
        $this->methodName = __METHOD__;
        $sql = "delete from ".$this->tableName." where ".$this->primaryKey." =".$id;
        $this->execSql($sql);
    }
    
    //获取总数
    function getTotalNum(){
        $this->methodName = __METHOD__;
        $rows = $this->selectList();
        return count($rows);
    }

    //分页部分代码
     function page(){
         $this->methodName = __METHOD__;
        return $this->paging();
    }
    
    /*//更新浏览量
    function updateCounter($id){
        $sql="update ".$this->tableName." set COUNTER=COUNTER+1 where ".$this->primaryKey."=".$id;
        $this->execSql($sql);
    }*/
    
    
    //更新Flag
    function updateFlag($type,$id,$value){
        $this->methodName = __METHOD__;
        $sql="update ".$this->tableName." set ".$type."='".$value."' where ".$this->primaryKey."=".$id;
        $this->execSql($sql);
    }
    //分页部分代码
     function pageHtml($module="",$condition=""){
         $this->methodName = __METHOD__;
        return $this->pagingHtml($module,$condition);
    }

    

}
?>