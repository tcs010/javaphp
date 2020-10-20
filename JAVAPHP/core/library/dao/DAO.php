<?php
/*
=============================================
* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : DAO.php
*  create date: 2010-12-8
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wangyuantao@coscoqd.com
=============================================
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
class DAO {
    //类名
	var $className ="";
    //方法名
    var $methodName = "";
    //数据库Handler
    //var $db =null;
    //表名
    var $tableName="";
	//表关键字
	var $primaryKey = "ID";
    //对应字段
    var $fields="*";
    //总记录数
    var $startNum=0;
    //每页记录数
    var $pageNum=50;
    //Sql语句变量
    var $sqlString = "";
    //默认的字符串排序
    var $orderString = "";
    //条件字符串
    var $conditionString ="";
    //限制字符串
    var $limitString ="";
    //调试
    var $debug = false;
    //日志
    var $log = false;


    //实例化
    function __construct(){
		$this->className ="DAO";
        //if($this->db==null){
			$this->db = new AppDb();

            if(APP_LOG){
                $this->log = true;
            }else{
                $this->log = false;
            }
    }
	function getDb(){
        if($this->db==null){
            $this->db = new AppDb();
        }
		return $this->db;
	}

    function closeDb(){

    }
    //调用不存在的方法时调用
    //function __call($name,$arguments) {
        //print("调用的方法[".$this->className."::".$name."]不存在!");
    //    print("【错误信息】：功能调用错误，不存在!");
        //exit;
    //}
    //输出测试信息
    function debugInfo($class,$method,$message){
        if($class==""){
            $class = $this->className;
        }
        echo "DEBUG: <b style='color:#333333'>".date("Y-m-d h:i:s")."</b>[<b style='color:green'>".$this->className."|".$method."</b>] <b style='color:#ec32ec'>".$message."</b><br />";
        //writeLog($message);
    }
    //写日志功能
    function logInfo($class,$method,$message){
        writeLog("[ ".$this->className."|".$method." ]".$message);
    }

    //获取全部
    function selectList($condition=""){

        if($condition!=""){
            $this->conditionString = $condition;
        }
        $result = array();
        $sql = "select ".$this->fields." from ".$this->tableName." ".$this->conditionString." ".$this->orderString." ".$this->limitString;
        $this->sqlString = $sql;
		if($this->debug){
            $this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
		}
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        $queryId = $this->getDb()->query($sql);
        $result = $this->processResult($queryId);
        $this->getDb()->free();
        return $result;
    }
    //根据Id查找一条记录
    function selectByPk($param){
        $result=array();
		$param = (($param==""||!isset($param)||$param==NULL))?"-1":$param;
        $this->conditionString=" where ".$this->primaryKey." in ('".$param."')";
        $sql="select ".$this->fields." from ".$this->tableName." ".$this->conditionString." ".$this->orderString." ".$this->limitString;
        $this->sqlString = $sql;
		if($this->debug){
			$this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
		}
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        $queryId = $this->getDb()->query($sql);
        $result = $this->processResult($queryId);
        $this->getDb()->free();
        return count($result)>0?$result[0]:$result;
    }
    //插入操作 params=array("ID"=>"123","NAME"=>"wit521")
    function insert($params=array()){
		$kstr = "";
		$vstr = "";
		if(gettype($params)=="array"){
			foreach($params as $k=>$v){
				$kstr .= "'".$k."',";
				$vstr .= "'".$v."',";
			}
			$kstr = substr($kstr,0,strlen($kstr)-1);
			$vstr = substr($str,0,strlen($str)-1);
		}else{
			$str = "'".str_replace("'","",$params)."'";
		}
		$sql="insert into ".$this->tableName."($kstr) values($vstr)";
        $this->sqlString = $sql;
        if($this->debug){
			$this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
		}
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
		$this->getDb()->query($sql);
        $this->getDb()->free();
    }
    //更新操作
    function update($params=array(),$condition=""){
		$vstr = "";
		if(gettype($params)=="array"){
			foreach($params as $k=>$v){
				$vstr .= $k."='".$v."',";
			}
			$vstr = substr($str,0,strlen($str)-1);
		}
		if($condition==""||$condition=NULL){
			$condition=str_replace("WHERE","",str_replace("where","",$this->conditionString));
		}
		$sql="update ".$this->tableName."set $vstr where $condition";
        $this->sqlString = $sql;
        if($this->debug){
			$this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
		}
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
		$this->getDb()->query($sql);
        $this->getDb()->free();
    }
    //删除操作
    function remove($params){
		$valstr = "";
		if(gettype($params)=="array"){
			foreach($params as $k=>$v){
				$valstr .= "'".$v."',";
			}
			$valstr = substr($valstr,0,strlen($valstr)-1);
		}else{
			$valstr = "'".str_replace("'","",$params)."'";
		}
        $sql="delete from ".$this->tableName." where ".$this->primaryKey." in (".$valstr.")";
        $this->sqlString = $sql;
		if($this->debug){
			$this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
		}
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        $this->getDb()->query($sql);
    }
    //获取记录数
    function totalNum($sql){
        $queryId = $this->getDb()->query($sql);
        $result = $this->processResult($queryId);
        $this->getDb()->free();
        return count($result);
    }
    //获取分页记录数组
    function pageRows($sql,$startNum=0,$pageNum=10)
    {
        $result=array();
        $sql=$sql." limit ".$startNum.",".$pageNum;
        $queryId = $this->getDb()->query($sql);
        $result = $this->processResult($queryId);
        $this->getDb()->free();
        return $result;
    }
    //分页
    function paging($condition="",$pageNum="",$lang="cn"){
        //分页数据 代码
        //$url = $_SERVER["REQUEST_URI"];
        $url = ($_SERVER['REQUEST_URI']==""?$_SERVER['QUERY_STRING']:$_SERVER['REQUEST_URI']);
       // echo "params:<br>";
        $params = convertUrlParams($url);
        
        //echo preg_replace("/\//","",$url)."<br>";
        //print_r($params);
       // echo "new params:<br>";
        unset($params["page"]); 
       // print_r($params);              
       // echo "<br>";
       $subDirArr = preg_split('/\//', $_SERVER["PHP_SELF"]);
       //print_r($subDirArr);
       $baseUrl="";
       $htmlPos = strpos($url,'.html',0);
       $xiegangPos = strpos($url,'/',0);
       if($htmlPos<0){
           if(count($subDirArr)>1){
                $baseUrl = $subDirArr[count($subDirArr)-1]."?".convertUrlQuery($params);
           }else{
                $baseUrl = $_SERVER["PHP_SELF"]."?".convertUrlQuery($params);
           }
       }else{
           if($xiegangPos>0){
               $baseUrl = $url;
           }else{
                $baseUrl = substr($url,1);
           }
           
       }
       
       //echo $_SERVER['REQUEST_URI']."|".$baseUrl."<br>";
        
        $curPage = isset($_REQUEST["page"])?$_REQUEST["page"]:1;
        $pageNum = $this->pageNum;
        if($condition!=""){
            $this->conditionString = $condition;
        }
        //当前页码 
        $curPage=!isset($curPage)?1:$curPage;
        //起始记录
        $startNum = ($curPage-1)*($pageNum);
        //查询语句
        $sql="select ".$this->fields." from ".$this->tableName." ".$this->conditionString.$this->orderString; 
        $this->sqlString = $sql;
        if($this->debug){
            $this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        $total=$this->totalNum($sql);
        //获取url
        $parse_url=parse_url($url);
        //单独取出URL的查询字串，例如:"page=2"
        $url_query=isset($parse_url["query"])?$parse_url["query"]:""; 
        //因为URL中可能包含了页码信息，我们要把它去掉，以便加入新的页码信息。
        $pageIndex = strpos($url,'page',0);
        if($pageIndex>0){
            $url=substr($url,0,$pageIndex);
        }
        $flagIndex=strpos($url,'?',0);
        $url=$flagIndex>0?$url.(substr($url,strlen($url)-1,1)=="&"?"":"&"):$url."?";
        
        //页码计算：
        $lastpg=ceil($total/$pageNum); //最后页，也是总页数
        $prepg=$curPage-1; //上一页
        $nextpg=($curPage==$lastpg ? 0 : $curPage+1);//下一页
         
        /*
          //开始分页导航条代码：
        if($total<=$pageNum){
            $pageNav="显示第 <B>".($total?($startNum+1):0)."</B>-<B>".min($startNum+$pageNum,$total)."</B> 条记录，共 $total 条记录 ";
        }else{
            $pageNav="共 $total 条记录 ";
        }
        //如果只有一页则跳出函数：
        if($lastpg<1) return false;
        $pageNav.=" <a href='".$url."page=1'>首页</a> ";
        if($prepg) 
            $pageNav.=" <a href='".$url."page=$prepg'>前一页</a> "; 
        else 
            $pageNav.=" 前一页 ";
        if($nextpg) 
            $pageNav.=" <a href='".$url."page=$nextpg'>后一页</a> "; 
        else 
            $pageNav.=" 后一页 ";
        $pageNav.=" <a href='".$url."page=$lastpg'>尾页</a> ";
          
        //下拉跳转列表，循环列出所有页码：
        $pageNav.=" 共 $curPage/$lastpg 页　转到第 <input type='text' name='txt_page' size='4' onkeydown='if(event.keyCode==13)window.location=\"".$url."page=\"+this.value;' ><!--<select name='topage' size='1' onchange='window.location=\"".$url."\"+this.value'>\n";
        for($i=1;$i<=$lastpg;$i++){
            if($i==$curPage){
                $pageNav.="<option value='page=$i' selected>$i</option>\n";
            }else {   
                $pageNav.="<option value='page=$i'>$i</option>\n";
            }
        }
        $pageNav.="</select>--> 页 <input type='button' value='确定' onclick='if(txt_page.value==\"\")txt_page.value=1;window.location=\"".$url."page=\"+txt_page.value;' >";

        */ 
       //print_r($_SERVER);
        
        //echo '<br />wangyuantao:http://'.$_SERVER['HTTP_HOST'].$_SERVER['QUERY_STRING']."<br />";
       /// echo("new:".($_SERVER['REQUEST_URI']==""?$_SERVER['QUERY_STRING']:$_SERVER['REQUEST_URI']));
       // $orgUrl = $_SERVER['HTTP_REFERER'];
        $url = $baseUrl;
        //判断是否开启rewrite
        if(APP_REWRITE){
            //处理url中.html转换成.php的格式
            $htmlPos = strpos($url,'.html',0);
            if($htmlPos>0){
                $urlTemp = substr($url,0,$htmlPos);
                $xiegangPos = strpos($urlTemp,'/',0);
                if($xiegangPos>0){
                    $urlTemp = substr($urlTemp,$xiegangPos+1);
                }
                //echo $url."|".$xiegangPos;
                //echo $urlTemp;
                //$url = preg_replace("/\//","",$url);
                $urlTempArr = preg_split("/-/",$urlTemp);
                //print_r($urlTempArr);
                //if(count($urlTempArr)>4){
                //    $url = "./index.php?mod=".$urlTempArr[0]."&act=".$urlTempArr[1]."&".$urlTempArr[2]."=".$urlTempArr[3]."&page=".$urlTempArr[4];
                //}else{
                //    $url= "./index.php?mod=".$urlTempArr[0]."&act=".$urlTempArr[1]."&".$urlTempArr[2]."=".$urlTempArr[3];
                //}
                //去掉page变量
                $url = "./index.php?mod=".$urlTempArr[0]."&act=".$urlTempArr[1]."&".$urlTempArr[2]."=".$urlTempArr[3];
            }
        }else{
            $pagePos = strpos($url,'page',0);
            if($pagePos>0){
                $url = substr($url,0,$pagePos-1);
            }
        }
        
        //echo $baseUrl."|".$url;
        
        //开始分页导航条代码：
        if($total<=$pageNum){
            $pageNav="显示第 <B>".($total?($startNum+1):0)."</B>-<B>".min($startNum+$pageNum,$total)."</B> 条记录，共 $total 条记录 ";
            $pageNavEn="Show <B>".($total?($startNum+1):0)."</B>-<B>".min($startNum+$pageNum,$total)."</B> Records，Sum total: $total  ";
        }else{
            $pageNavEn="Sum total: $total  ";
            $pageNav="共 $total 条记录 ";
        }
        //去掉【显示 第xxx条记录】
        $pageNavEn="Sum total: $total  ";
        $pageNav="共 $total 条记录 ";
        //如果只有一页则跳出函数：
        if($lastpg<1) return false;
        $pageNav.=" <a href='".$url.(strpos($url,'?',0)>0?'&':"?")."page=1'>首页</a> ";  
        $pageNavEn.=" <a href='".$url.(strpos($url,'?',0)>0?'&':"?")."page=1'>First</a> ";
        if($prepg) {
            $pageNav.=" <a href='".$url.(strpos($url,'?',0)>0?'&':"?")."page=".$prepg."'>前一页</a> ";
            $pageNavEn.=" <a href='".$url.(strpos($url,'?',0)>0?'&':"?")."page=".$prepg."'>Prev</a>";
        } else {
            $pageNav.=" 前一页 "; 
            $pageNavEn.="Prev"; 
        }
        if($nextpg) {
            $pageNav.=" <a href='".$url.(strpos($url,'?',0)>0?'&':"?")."page=".$nextpg."'>后一页</a> ";
            $pageNavEn.=" <a href='".$url.(strpos($url,'?',0)>0?'&':"?")."page=".$nextpg."'>Next</a>";
        } else {
            $pageNav.=" 后一页 "; 
            $pageNavEn.="Next"; 
        }
        $pageNav.=" <a href='".$url.(strpos($url,'?',0)>0?'&':"?")."page=".$lastpg."'>尾页</a> "; 
        $pageNavEn.=" <a href='".$url.(strpos($url,'?',0)>0?'&':"?")."page=".$lastpg."'>Last</a> ";
         
         //index.php?/training-index-type-21-1.html=
        //echo "ddddddddd".$url."<BR>".$pageNav;
          
        //下拉跳转列表，循环列出所有页码：
        $pageNav.=" 共 $curPage/$lastpg 页　转到第 <input type='text' name='txt_page' size='4' onkeydown='if(event.keyCode==13)window.location=\"".$url."page=\"+this.value;' >"; 
        $pageNavEn.=" Pages: $curPage/$lastpg 　Goto <input type='text' name='txt_page' size='2' onkeydown='if(event.keyCode==13)window.location=\"".$url."page=\"+this.value;' >";
        $pageNav.=" 页 <input type='button' value='确定' onclick='if(txt_page.value==\"\")txt_page.value=1;window.location=\"".$url.(strpos($url,'?',0)>0?'&':"?")."page=\"+txt_page.value;' >";  
        $pageNavEn.="  <input type='button' value='OK' onclick='if(txt_page.value==\"\")txt_page.value=1;window.location=\"".$url.(strpos($url,'?',0)>0?'&':"?")."page=\"+txt_page.value;' >";

        
        
        //数据列表
        $rows=$this->pageRows($sql,$startNum,$pageNum);

        //释放数据库链接
        $this->getDb()->free();
        
        //对分页字符串进行编码
        //$pageNav = $pageNav;

        return array("total"=>$total,"rows"=>$rows,"pageNav"=>$pageNav,"pageNavEn"=>$pageNavEn,"curPage"=>$curPage,"pageNum"=>$pageNum,"url"=>substr($url,0,strlen($url)-1));
        
    }


     //分页
    function pagingHtml($module,$condition,$pageNum="",$url=""){
        //分页数据 代码
        //$url = $_SERVER["PHP_SELF"];

        //$nameArr =

        //$curPage = isset($_REQUEST["page"])?$_REQUEST["page"]:1;
        $pageNum = $this->pageNum;


        //当前页码
        //$curPage=!isset($curPage)?1:$curPage;
        //起始记录
        $startNum = ($curPage-1)*($pageNum);
        //查询语句
        $sql="select ".$this->fields." from ".$this->tableName." ".$condition.$this->orderString;
        $this->sqlString = $sql;
        $total=$this->totalNum($sql);



        //获取url
        //$parse_url=parse_url($url);
        //print_r($parse_url);
        //单独取出URL的查询字串，例如:"page=2"
        //$url_query=isset($parse_url["query"])?$parse_url["query"]:"";
        //因为URL中可能包含了页码信息，我们要把它去掉，以便加入新的页码信息。
        //$pageIndex = strpos($url,'list',0);
        //if($pageIndex>0){
        //    $url=substr($url,0,$pageIndex);
        //}
        //$flagIndex=strpos($url,'?',0);
        //$url=$flagIndex>0?$url.(substr($url,strlen($url)-1,1)=="&"?"":"&"):$url."?";
        //页码计算：
        $lastpg=ceil($total/$pageNum); //最后页，也是总页数


        for($i=0;$i<$lastpg;$i++){
            $arr = new ModelMap();
            $arr->put("title","新闻显示页");
            $arr->put("vo.title","这里是第一条新闻");
            $arr->put("list","<li>这里是列表部分内容</li><li>这里是列表部分内容</li><li>这里是列表部分内容</li><li>这里是列表部分内容</li><li>这里是列表部分内容</li><li>这里是列表部分内容</li><li>这里是列表部分内容</li><li>这里是列表部分内容</li><li>这里是列表部分内容</li><li>这里是列表部分内容</li><li>这里是列表部分内容</li>");
            $module = "wit";
            $filename = "list_".$total."_".($i+1);


            //页码计算
            $curPage=$i+1;
            $prepg=$curPage-1; //上一页
            $nextpg=($curPage==$lastpg ? 0 : $curPage+1);//下一页
            $startNum = ($curPage-1)*($pageNum);

            if($total<=$pageNum){
                $pageNav="显示第 <B>".($total?($startNum+1):0)."</B>-<B>".min($startNum+$pageNum,$total)."</B> 条记录，共 $total 条记录 ";
            }else{
                $pageNav="共 $total 条记录 ";
            }
             if($lastpg>1) {
                 if($prepg) $pageNav.=" <a href='list_".$total."_".$prepg.".html'>前一页</a> "; else $pageNav.=" 前一页 ";
                 if($nextpg) $pageNav.=" <a href='list_".$total."_".$nextpg.".html'>后一页</a> "; else $pageNav.=" 后一页 ";
             }
            $pageNav.=" <a  href='list_".$total."_".$lastpg.".html'>尾页</a> ";
            //echo $pageNav;
            $arr->put("pageNav","$pageNav");

            //生成页面
            if($i==0){
                $this->createHTML($module,"index",$template="news/index.html",$arr,$ymFolder=true);
            }
            $this->createHTML($module,$filename,$template="news/index.html",$arr,$ymFolder=true);

        }

        //开始分页导航条代码：
        if($total<=$pageNum){
            $pageNav="显示第 <B>".($total?($startNum+1):0)."</B>-<B>".min($startNum+$pageNum,$total)."</B> 条记录，共 $total 条记录 ";
        }else{
            $pageNav="共 $total 条记录 ";
        }
        //如果只有一页则跳出函数：
        if($lastpg<1) return false;
        $pageNav.=" <a href='".$url."page=1'>首页</a> ";
        if($prepg) $pageNav.=" <a href='".$url."page=$prepg'>前一页</a> "; else $pageNav.=" 前一页 ";
        if($nextpg) $pageNav.=" <a href='".$url."page=$nextpg'>后一页</a> "; else $pageNav.=" 后一页 ";
        $pageNav.=" <a href='".$url."page=$lastpg'>尾页</a> ";

        //下拉跳转列表，循环列出所有页码：
        $pageNav.=" 共 $curPage/$lastpg 页　转到第 <input type='text' name='txt_page' size='4' onkeydown='if(event.keyCode==13)window.location=\"".$url."page=\"+this.value;' ><!--<select name='topage' size='1' onchange='window.location=\"".$url."\"+this.value'>\n";
        for($i=1;$i<=$lastpg;$i++){
            if($i==$curPage){
                $pageNav.="<option value='page=$i' selected>$i</option>\n";
            }else {
                $pageNav.="<option value='page=$i'>$i</option>\n";
            }
        }
        $pageNav.="</select>--> 页 <input type='button' value='确定' onclick='if(txt_page.value==\"\")txt_page.value=1;window.location=\"".$url."page=\"+txt_page.value;' >";

        //数据列表
        $rows=$this->pageRows($sql,$startNum,$pageNum);

        //对分页字符串进行编码
        //$pageNav = $pageNav;

        return array("total"=>$total,"rows"=>$rows,"pageNav"=>$pageNav,"curPage"=>$curPage,"pageNum"=>$pageNum,"url"=>substr($url,0,strlen($url)-1));

    }

    //查询操作
    function execQuery($sql,$params=array()){
        $result = array();
        if($params!=NULL){
            foreach($params as $key=>$value){
                $sql = str_replace(":".$key.",","'".$value."',",$sql);
                $sql = str_replace(":".$key." ","'".$value."' ",$sql);
                $sql = str_replace(":".$key.")","'".$value."')",$sql);
            }
        }
        $this->sqlString = $sql;
        if($this->debug){
            $this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        $queryId = $this->getDb()->query($sql);
        $result = $this->processResult($queryId);
        $this->getDb()->free();
        return $result;
    }
    //查询一条记录操作
    function execQueryForOne($sql,$params=array()){
        foreach($params as $key=>$value){
            $sql = str_replace(":".$key.",","'".$value."',",$sql);
            $sql = str_replace(":".$key." ","'".$value."' ",$sql);
            $sql = str_replace(":".$key.")","'".$value."')",$sql);
        }
        $this->sqlString = $sql;
        if($this->debug){
            $this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        $queryId = $this->getDb()->query($sql);
        $result = $this->processResult($queryId);
        if(count($result)>0)  return $result[0];
        $this->getDb()->free();
        return $result;
    }
    /*******************
    * 功能：查询一条操作
    *  说明：只支持一维
    * @param mixed $_tableName
    * @param mixed $_params
    * @param mixed $_conditionArr
    * @param mixed $_orderStr
    * @param mixed $_limit
    */
    function queryForOne($_tableName,$_params=array(),$_conditionArr=array(),$_orderStr="",$_limitStr=""){
        $_sql = "";
        $_fieldsStr = "";
        $_conditionStr = "";
        $_limitStr ="";
        //字段部分处理
        $i = 0;
        foreach($_params as $_key=>$_value){
            if(is_array($_value)){
                exit;
            }
            $_fieldsStr .= ($i==count($_params)-1)?$_value:$_value.",";
            $i++;
        }
        //条件部分处理
        if(count($_conditionArr)>0){
            $_conditionStr = "where ";
            $j = 0;
            foreach($_conditionArr as $_key1=>$_value1){
                if(is_array($_value1)){
                    exit;
                }
               //值处理
               if(is_null($_value1)){
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."=''":$_key1."='' and ";
               }else if(!preg_match("/^(0|[1-9][0-9]*)$/",$_value1)){
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."="."'".$_value1."'":$_key1."="."'".$_value1."' and ";
               }else if(is_numeric($_value1)){
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."=".$_value1:$_key1."=".$_value1." and ";
               }else{
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."="."'".$_value1."'":$_key1."="."'".$_value1."' and ";
               }
                $j++;
            }
        }else{
            $_conditionStr = $this->conditionString;
        }

        //排序部分处理
        if($_orderStr==""){
           $_orderStr = $this->orderString;
        }

        //limit部分处理
        if($_limitStr==""){
           $_limitStr = $this->limitString;
        }


        //组合sql语句
        $sql = "select ".$_fieldsStr." from ".$_tableName." ".$_conditionStr.$_orderStr.$_limitStr;
        $this->sqlString = $sql;
        if($this->debug){
            $this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        //$this->getDb()->query($sql);
        $queryId = $this->getDb()->query($sql);
        $result = $this->processResult($queryId);
        $this->getDb()->free();
        if(count($result)>0){
            return $result[0];
        }else {
            return array();
        }
    }
    /*******************
    * 功能：执行sql语句
    *
    * @param mixed $sql
    */
    function execSql($sql=""){
        $this->sqlString = $sql;
        if($this->debug){
            $this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        try{
             $queryId = $this->getDb()->query($sql);
             $result = $this->processResult($queryId);
        }catch(Exception $e){
             exit;
        }

        $this->getDb()->free();
        return $result;
    }
    /*******************
    * 功能：查询操作
    *  说明：只支持一维
    * @param mixed $_tableName
    * @param mixed $_params
    * @param mixed $_conditionArr
    * @param mixed $_orderStr
    * @param mixed $_limit
    */
    function query($_tableName,$_params=array(),$_conditionArr=array(),$_orderStr="",$_limitStr=""){
        $_sql = "";
        $_fieldsStr = "";
        $_conditionStr = "";
        $_limitStr ="";
        //字段部分处理
        $i = 0;
        foreach($_params as $_key=>$_value){
            if(is_array($_value)){
                exit;
            }
            $_fieldsStr .= ($i==count($_params)-1)?$_value:$_value.",";
            $i++;
        }
        //条件部分处理
        if(count($_conditionArr)>0){
            $_conditionStr = "where ";
            $j = 0;
            foreach($_conditionArr as $_key1=>$_value1){
                if(is_array($_value1)){
                    exit;
                }
               //值处理
               if(is_null($_value1)){
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."=''":$_key1."='' and ";
               }else if(!preg_match("/^(0|[1-9][0-9]*)$/",$_value1)){
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."="."'".$_value1."'":$_key1."="."'".$_value1."' and ";
               }else if(is_numeric($_value1)){
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."=".$_value1:$_key1."=".$_value1." and ";
               }else{
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."="."'".$_value1."'":$_key1."="."'".$_value1."' and ";
               }
                $j++;
            }
        }else{
            $_conditionStr = $this->conditionString;
        }


        //排序部分处理
        if($_orderStr==""){
           $_orderStr = $this->orderString;
        }

        //limit部分处理
        if($_limitStr==""){
           $_limitStr = $this->limitString;
        }


        //组合sql语句
        $sql = "select ".$_fieldsStr." from ".$_tableName." ".$_conditionStr.$_orderStr.$_limitStr;
        $this->sqlString = $sql;
        if($this->debug){
            $this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        $queryId = $this->getDb()->query($sql);
        $result = $this->processResult($queryId);
        $this->getDb()->free();
        return $result;

    }
    /*******************
    * 功能：插入操作
    * 说明：只支持一维和二维数组
    * @param mixed $_tbName    //操作表名称
    * @param mixed $_params    //插入字段名值数组
    */
    function execInsert($_tableName="",$_params=array()){
        $_sql = "";
        $_fieldsStr = "(";
        $_valuesStr = "(";
        $i = 0;
        foreach($_params as $_key=>$_value){
            if(is_array($_value)){
                $ii = 0 ;  //位置标识
                foreach($_value as $_key1=>$_value1){
                   if(is_array($_value1)){
                       exit;
                   }
                   //字段处理
                   if($i==0){

                       $_fieldsStr .= ($ii==count($_value)-1)?$_key1."":$_key1.",";
                   }
                   //值处理
                   if(is_null($_value1)){
                       $_valuesStr .= ($ii==count($_value)-1)?"''":"'',";
                   }else if(!preg_match("/^(0|[1-9][0-9]*)$/",$_value1)){
                       $_valuesStr .= ($ii==count($_value)-1)?"'".htmlspecialchars($_value1)."'":"'".htmlspecialchars($_value1)."',";
                   }else if(is_numeric($_value1)){
                       $_valuesStr .= ($ii==count($_value)-1)?$_value1."":$_value1.",";
                   }else{
                       $_valuesStr .= ($ii==count($_value)-1)?"'".htmlspecialchars($_value1)."'":"'".htmlspecialchars($_value1)."',";
                   }
                   //位置标识加1
                   $ii++;

                }
                //右括号
                $_fieldsStr .= ($i==count($_params)-1)?")":"";
                $_valuesStr .= ($i==count($_params)-1)?")":"),(";
            }else{
               $_fieldsStr .= ($i==count($_params)-1)?$_key:$_key.",";
               if(is_null($_value)){
                   $_valuesStr .= ($i==count($_params)-1)?"''":"'',";
               }else if(!preg_match("/^(0|[1-9][0-9]*)$/",$_value)){
                   $_valuesStr .= ($i==count($_params)-1)?"'".htmlspecialchars($_value)."'":"'".htmlspecialchars($_value)."',";
               }else if(is_numeric($_value)){
                   $_valuesStr .= ($i==count($_params)-1)?$_value."":$_value.",";
               }else{
                   $_valuesStr .= ($i==count($_params)-1)?"'".htmlspecialchars($_value)."'":"'".htmlspecialchars($_value)."',";
               }
               //右括号
               $_fieldsStr .= ($i==count($_params)-1)?")":"";
               $_valuesStr .= ($i==count($_params)-1)?")":"";
            }

            $i++;
        }
        //组合sql语句
        $sql = "insert into ".$_tableName.$_fieldsStr." values".$_valuesStr;
        $this->sqlString = $sql;
        if($this->debug){
            $this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        $this->getDb()->query($sql);
        $this->getDb()->free();
    }

    /*******************
    * 功能：更新操作
    * 说明：只支持一维
    * @param mixed $_tbName    //操作表名称
    * @param mixed $_params    //插入字段名值数组
    */
    function execUpdate($_tableName,$_params=array(),$_conditionArr=array()){
        $_sql = "";
        $_valuesStr = "";
        $_conditionStr = "";
        //赋值部分处理
        $i = 0;
        foreach($_params as $_key=>$_value){
            if(is_array($_value)){
                exit;
            }
            $_value = htmlspecialchars($_value,ENT_QUOTES);
           //值处理
           if(is_null($_value)){
               $_valuesStr .= ($i==count($_params)-1)?$_key."=''":$_key."='',";
           }else if(!preg_match("/^(0|[1-9][0-9]*)$/",$_value)){
               $_valuesStr .= ($i==count($_params)-1)?$_key."="."'".$_value."'":$_key."="."'".$_value."',";
           }else if(is_numeric($_value)){
               $_valuesStr .= ($i==count($_params)-1)?$_key."=".$_value:$_key."=".$_value.",";
           }else{
               $_valuesStr .= ($i==count($_params)-1)?$_key."="."'".$_value."'":$_key."="."'".$_value."',";
           }
            $i++;
        }
        //条件部分处理
        if(count($_conditionArr)>0){
            $j = 0;
            foreach($_conditionArr as $_key1=>$_value1){
                if(is_array($_value1)){
                    exit;
                }
               //值处理
               if(is_null($_value1)){
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."=''":$_key1."='' and ";
               }else if(!preg_match("/^(0|[1-9][0-9]*)$/",$_value1)){
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."="."'".$_value1."'":$_key1."="."'".$_value1."' and ";
               }else if(is_numeric($_value1)){
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."=".$_value1:$_key1."=".$_value1." and ";
               }else{
                   $_conditionStr .= ($j==count($_conditionArr)-1)?$_key1."="."'".$_value1."'":$_key1."="."'".$_value1."' and ";
               }
                $j++;
            }
        }else{
            $_conditionStr = $this->conditionString;
        }

        //组合sql语句
        $sql = "update ".$_tableName." set ".$_valuesStr." where ".$_conditionStr;
        $this->sqlString = $sql;
        if($this->debug){
            $this->debugInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        if($this->log){
            $this->logInfo($this->className,$this->methodName==""?__FUNCTION__:$this->methodName,$sql);
        }
        $this->getDb()->query($sql);
        $this->getDb()->free();
    }
    /*
    //更新操作
    function execUpdate($sql,$params=array()){
        foreach($params as $key=>$value){
            if(is_null($value)){
                if(strpos(strtolower($sql),"insert")!==false){//插入
                    $sql = str_replace("(:".$key.",","(",$sql);//start
                    $sql = str_replace(",:".$key.",",",",$sql);//middle
                    $sql = str_replace(",:".$key.")",")",$sql);//end
                    $sql = str_replace("(".$key.",","(",$sql);//start
                    $sql = str_replace(",".$key.",",",",$sql);//middle
                    $sql = str_replace(",".$key.")",")",$sql);//end
                }else if(strpos(strtolower($sql),"update")!==false){//编辑
                    $sql = str_replace($key."=:".$key.",","",$sql);
                    $sql = str_replace(",".$key."=:".$key." where"," where",$sql);
                }
            }else{
                $sql = str_replace(":".$key.",","'".$value."',",$sql);
                $sql = str_replace(":".$key." ","'".$value."' ",$sql);
                $sql = str_replace(":".$key.")","'".$value."')",$sql);
                $sql = str_replace(":".$key,"'".$value."'",$sql);

            }
        }
        if($this->debug){
            echo $sql."<br />";
        }
        $this->getDb()->query($sql);
    }
    */
    //将列表行转换成VO形式的数据
    function listMapper($list){
        $result = array();
        foreach($list as $index=>$row){
            array_push($result,$this->rowMapper($row));
        }
        return $result;
    }
    //处理QueryId,获取result
    function processResult($queryId){
        $result = array();
        $temp = array();
        if(!is_bool($queryId)){
            while ($r = mysql_fetch_assoc($queryId)){
                $temp[]=$r;
            }
            if(count($temp)>0){
                foreach($temp as $k=>$v){
                    array_push($result,$v);
                }
            }
            return $result;
        }else{
            return $queryId;
        }

    }


}
?>