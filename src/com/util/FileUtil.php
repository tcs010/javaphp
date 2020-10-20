<?php
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}

/*!
 * upload demo for php
 * @requires xhEditor
 * 
 * @author Yanis.Wang<yanis.wang@gmail.com>
 * @site http://pirate9.com/
 * @licence LGPL(http://www.opensource.org/licenses/lgpl-license.php)
 * 
 * @Version: 0.9.2 build 100225
 * 
 * 注：本程序仅为演示用，请您根据自己需求进行相应修改，或者重开发。
 */
header('Content-Type: text/html; charset=UTF-8');
error_reporting(0);
if(!isset($_REQUEST["upload_file_ele_index"])||$_REQUEST["upload_file_ele_index"]==""){
    echo "非法进入！";
    exit;
}

function uploadfile($inputname,$waterArray=array(),$waterFlag=false)
{
    
	$immediate=isset($_GET['immediate'])?$_GET['immediate']:0;
	$attachdir='../../../../../../../WebRoot/upload';//上传文件保存路径，结尾不要带/<br />
	//$attachdir=constant("SYS_UPLOAD_fILE");//上传文件保存路径
	$dirtype=1;//1:按天存入目录 2:按月存入目录 3:按扩展名存目录  建议使用按天存
	$maxattachsize=200000000;//最大上传大小，默认是100M
	$upext='txt,rar,zip,jpg,bmp,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid,pdf,doc,xls,flv';//上传扩展名
	$msgtype=2;//返回上传参数的格式：1，只返回url，2，返回参数数组
	$filePath="";
	
	$err = "";
	$msg = "";
	if(!isset($_FILES[$inputname]))return array('err'=>'文件域的name错误或者没选择文件','msg'=>$msg);
	$upfile=$_FILES[$inputname];
	//print_r($upfile);
	
	if(!empty($upfile['error']))
	{
		switch($upfile['error'])
		{
			case '1':
				$err = '文件大小超过了php.ini定义的upload_max_filesize值';
				break;
			case '2':
				$err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
				break;
			case '3':
				$err = '文件上传不完全';
				break;
			case '4':
				$err = '无文件上传';
				break;
			case '6':
				$err = '缺少临时文件夹';
				break;
			case '7':
				$err = '写文件失败';
				break;
			case '8':
				$err = '上传被其它扩展中断';
				break;
			case '999':
			default:
				$err = '无有效错误代码';
		}
	}else if(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none'){
        $err = '无文件上传';
    }else{
		$temppath=$upfile['tmp_name'];
		$fileinfo=pathinfo($upfile['name']);
		$extension=$fileinfo['extension'];
		if(preg_match('/'.str_replace(',','|',$upext).'/i',$extension))
		{
			$filesize=filesize($temppath);
			//获取上传文件的详细信息
			$info = getimagesize($temppath);
			//print_r($info);
			if($filesize > $maxattachsize){
                $err='文件大小超过'.$maxattachsize.'字节';
            }else{
				switch($dirtype){
					case 1: $attach_subdir = 'day_'.date('ymd'); break;
					case 2: $attach_subdir = 'month_'.date('ym'); break;
					case 3: $attach_subdir = 'ext_'.$extension; break;
				}
				$attach_dir = $attachdir.'/'.$attach_subdir;
				$filePath =  $attachdir.'\/'.$attach_subdir;
				if(!is_dir($attach_dir)){
					@mkdir($attach_dir, 0777);
					@fclose(fopen($attach_dir.'/index.htm', 'w'));
				}
				PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
				$filename=date("YmdHis").mt_rand(1000,9999).'.'.$extension;
				$target = $attach_dir.'/'.$filename;
				$filePath = $filePath.'\/'.$filename;
				
				move_uploaded_file($upfile['tmp_name'],$target);
				chmod($target,0755);
				if($immediate=='1')$target='!'.$target;
				if($msgtype==1)$msg=$target;
				else $msg=array('url'=>$target,'localname'=>$upfile['name'],'id'=>'1');//id参数固定不变，仅供演示，实际项目中可以是数据库ID
			}
		}
		else $err='上传文件扩展名必需为：'.$upext;

		@unlink($temppath);
	}
    
    //添加水印
    if($waterFlag){
        include('./waterImage.php');
        //图片水印 
        for($wi=0;$wi<count($waterArray);$wi++){
            imageWaterMark($target,$waterArray[$wi][1],$waterArray[$wi][0]); 
        }
    }
     
    //获取绝对路径
    $http = "http://";
    $urlArr = preg_split("/\//",substr($_SERVER['HTTP_REFERER'],7));
    $http.=$urlArr[0];  
    if(count($urlArr)>2){
        if($urlArr[1]!="WebRoot"){
            $http.="/".$urlArr[1];
        }
    }
	//获取绝对路径
	//$lastPath=$http."/WebRoot/upload/".$attach_subdir."/".$filename;
    $lastPath="./WebRoot/upload/".$attach_subdir."/".$filename;  
    
	//$result = $target;
	$result = "{";
	$result .="error: '" . $err."',\n";        
	$result .="msg: '文件上传成功!"."',\n";
	$result .="url: '" . $lastPath . "'\n";
	$result .="}";
	return $result;
	//return $filePath;
}

/*==========================================================
*                     图片上传部分开始
==========================================================*/
$picName = $_REQUEST["upload_file_ele_index"];
//无水印
/*$json = uploadfile($picName); 
//带水印
/*
$waterArray = array(
    //array("./waterImg.png","0")//随机位置
    array("./waterImg.png","1")//顶端居左
    ,array("./waterImg.png","2")//顶端居中
    ,array("./waterImg.png","3")//顶端居右
    ,array("./waterImg.png","4")//中部居左
    ,array("./waterImg.png","5")//中部居中
    ,array("./waterImg.png","6")//中部居右
    ,array("./waterImg.png","7")//底端居左
    ,array("./waterImg.png","8")//底端居中
    ,array("./waterImg.png","9")//底端居右
);
$json = uploadfile($picName,$waterArray,true);
*/
$waterArray = array(

    array("../../../../../../../WebRoot/themes/default/images/waterImage.png","5")//中部居中

);

$json = uploadfile($picName,$waterArray,false);    
//print_r($json);
//输出图片信息
echo $json;
//$result = "{error: '' ,msg: '文件上传成功!',url: './dfdsf/fserfdsf/fsdre'}";
//echo $result;

?>