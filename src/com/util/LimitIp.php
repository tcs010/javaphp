<?php
/*********************************************
 * 文件：limitip.php
 * 用途：IP限制程序
 * 版本：v1.0
 * 日期：2005-1-7 12:34
 * 作者：heiyeluren (heiyeluren@163.com)
 * 版权：http://www.unixsky.net
 *********************************************/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}

error_reporting(7);
session_start();

// 发送字符头信息
if ($headercharset)
 header("Content-Type:text/html; charset=gb2312");

// 加载公共文件
require_once("config.php");
require_once("global.php");
require_once("db_mysql.php");

/***************** 进行客户端能否访问本网站校验 ************/

// 获取客户端IP
if(getenv('HTTP_CLIENT_IP')) {
 $client_ip = getenv('HTTP_CLIENT_IP');
} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
 $client_ip = getenv('HTTP_X_FORWARDED_FOR');
} elseif(getenv('REMOTE_ADDR')) {
 $client_ip = getenv('REMOTE_ADDR');
} else {
 $client_ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
}

// 分解客户端IP
$cip = explode(".", $client_ip);

// 连接数据库
$db = new DB_Sql();
$err = $db->connect();

/*  限制远程IP访问, PS: 这段代码真晕，呵呵，用了8个if, -_-#  */
// 从数据库中提取存储的要限制的IP地址
$query_str = "SELECT limit_ip FROM us_limitip";
$db->query($query_str);
// 把结果循环提取，一个个进行校验
while ($db->next_record())
{
 $limit_ip = $db->f("limit_ip");
 $lip = explode(".", $limit_ip);
 // 如果限制IP的第一个是*或者是0的话就跳到错误页
 if (($lip[0]=='*') || ($lip[0]=='0'))
  header("Location:../error.php?errId=300");
 // 如果刚好客户端IP等于我们限制IP就跳到错误页
 if ($client_ip==$limit_ip)
  header("Location:../error.php?errId=300");
 // 如果第一组IP一致进行第二组IP的匹配
 if ($cip[0] == $lip[0])
 {
  // 如果第二组限制IP是*就跳到错误页
  if ($lip[1]=='*')
   header("Location:../error.php?errId=300");
  // 第二组IP匹配就进行第三组IP匹配
  if ($cip[1]==$lip[1])
  {
   // 如果第三组限制字符是*就跳到错误页
   if ($lip[2]=='*')
    header("Location:../error.php?errId=300");
   // 如果第三组IP匹配就跳到第三组校验
   if ($cip[2]==$lip[2])
   {
    // 如果第四组限制IP是*或0就跳到错误页
    if (($lip[3]=='*') || ($lip[3]=='0'))
     header("Location:../error.php?errId=300");
   }
  }
 }  
}
// 释放数据库查询结果
$db->free();

/****************** IP校验结束 ******************/

?>