<?php 
/*
/* Copyright (c) 2011-2013 Create By WangYuantao
=============================================
*  Function   : AdditiveCode.php
*  create date: 2011-09-06 13:09:38
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wit521@gmail.com
*  Http       : www.java-php.com
=============================================
*/ 
if (!defined("IN_APP")){
    exit( "Access Denied" );
}
session_start();//将随机数存入session中
$_SESSION["AUTHNUM"]="";
//生成验证码图片 
Header("Content-type: image/PNG"); 
srand((double)microtime()*1000000);//播下一个生成随机数字的种子，以方便下面随机数生成的使用

$im = imagecreate(41,20); //制定图片背景大小

$black = ImageColorAllocate($im, 0,0,0); //设定三种颜色
$white = ImageColorAllocate($im, 255,255,255); 
$red = ImageColorAllocate($im, 255,0,0); 
$gray = ImageColorAllocate($im, 200,200,200); 
$ligntgray = ImageColorAllocate($im, 240,240,240); 

imagefill($im,0,0,$ligntgray); //采用区域填充法，设定（0,0）

while(($authnum=rand()%10000)<1000);
//将四位整数验证码绘入图片 
$_SESSION["AUTHNUM"]=$authnum;
imagestring($im, 5, 3, 3, $authnum, $red);
// 用 col 颜色将字符串 s 画到 image 所代表的图像的 x，y 座标处（图像的左上角为 0, 0）。
//如果 font 是 1，2，3，4 或 5，则使用内置字体
/*
for($i=0;$i<200;$i++) //加入干扰象素 
{ 
$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); 
} 
*/
ImagePNG($im); 
ImageDestroy($im); 
?>