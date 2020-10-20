<?php
/*
=============================================
* Copyright (c) 2010 Create By WangYuantao
=============================================
*  Function   : Common.php
*  create date: 2010-12-9
*  create By  : Wang Yuantao
*  QQ         : 23479184
*  Email      : wangyuantao@coscoqd.com
=============================================
*/

//导入通用函数
if (!defined("TCS_APP")){
	exit( "Access Denied" );
}

function import($path){
  $file =  APP_CONFIG_PATH."/../src/".str_replace(".","/",$path).".php";
  $pathArr = preg_split("/./",$path);
  if(!file_exists($file)){
      //exit("【加载错误】加载文件失败！原因：文件不存在！");
      exit(encodeUtf8("【加载错误】加载文件"."src/".str_replace(".","/",$path).".php"."失败！原因：文件不存在！"));
  }else{
      if(count($path)>0&&!class_exists($pathArr[count($pathArr)-1])){
          require_once($file);
      }
      require_once($file);
  }

 }

 //表字段生成vo变量  例如：CODE_NAME =>codeName
function tbFieldToVoField($field){
    $result = "";
    $fieldArr = preg_split("/_/",strtolower($field));
    for($i=0;$i<count($fieldArr);$i++){
        if($i==0){
            $result.=strtolower($fieldArr[$i]);
        }else{
            $result.=ucwords($fieldArr[$i]);
        }
    }
    return $result;
}
//$arr数组的值转换为逗号分隔的String
function arrToStr($arr){
    $result = "";
    $i=0;
    foreach ($arr as $key=>$value){
        if($i++==0){
            $result.=strtoupper($value);
        }else{
            $result.=",".strtoupper($value);
        }
    }
    return $result;
}

//model对象转换成JSON对象
function modelToJson($vo){

    $result = "";
    $i=0;
    foreach ($vo as $key=>$value){

        switch(gettype($value)){

            case "string":
                   $result.="{\"".$key."\":\"".$value."\"}";
            break;
            case "array":
                $result .= "[";
                foreach ($value as $k=>$v){
                    if($j++==0){
                        $result.="{";
                    }else{
                        $result.=",{";
                    }
                    $m=0;
                     foreach ($v as $k1=>$v1){
                         if($m++==0){
                            $result.="\"".$k1."\":\"".$v1."\"";
                         }else{
                            $result.=",\"".$k1."\":\"".$v1."\"";
                         }
                     }
                     $result.="}";
                }
                $result .= "]";
            break;
                case "object":
                $j=0;
                $result.="{";
                foreach ($value as $k=>$v){
                    if($j++==0){
                        $result.="\"".$k."\":\"".$v."\"";
                    }else{
                        $result.=",\"".$k."\":\"".$v."\"";
                    }
                }
                 $result.="}";
            break;
        }

    }
    $result .= "";
    return $result;
}
//生成文件 $arr数组
function createFile($arr,$filename){
    $fp2=fopen($filename,"w");
    foreach ($arr as $key=>$value){
        $out=$value."\r\n";
        fwrite($fp2,$out);
    }
    fclose($fp2);
}
//编码转换为UTF8
function encodeUtf8($str){
    return iconv("GB2312","UTF-8",$str);
    //return $str;
}
//utf8转换为gb2312
function utf8ToGb2312($str){
    return iconv("UTF-8","GB2312",$str);
}
function iso8859ToGb2312($str){
    return iconv("ISO-8859-1","GB2312",$str);
}

//gbk转换为utf8
function gbkToUtf8($str){
    return iconv("gbk","UTF-8",$str);
}
//gb2312转换为utf8
function gb2312ToUtf8($str){
    return iconv("GB2312","UTF-8",$str);
}

function redirect($uri,$msg="") {
    //session_destroy();
    if(isset($msg)&&$msg!=""){
        echo "<script>alert('".$msg."');location.href='".$uri."'</script>";
    }else{
        echo "<script>location.href='".$uri."'</script>";
    }

}

function _action_observer(){
    $_ao = "QVBQX";
    $_spk_arr = preg_split("/,/",APP_V_ID);
    for($i=0;$i<count($_spk_arr);$i++){
        if(_escape_("ISO-8859-1")==$_spk_arr[$i]){
            return true;
        }
     }
     exit;
}

function escape($str) {
    preg_match_all ( "/[\xc2-\xdf][\x80-\xbf]+|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}|[\x01-\x7f]+/e", $str, $r );
    //匹配utf-8字符，
    $str = $r [0];
    $l = count ( $str );
    for($i = 0; $i < $l; $i ++) {
        $value = ord ( $str [$i] [0] );
        if ($value < 223) {
            $str [$i] = rawurlencode ( utf8_decode ( $str [$i] ) );
        //先将utf8编码转换为ISO-8859-1编码的单字节字符，urlencode单字节字符.
        //utf8_decode()的作用相当于iconv("UTF-8","CP1252",$v)。
        } else {
            $str [$i] = "%u" . strtoupper ( bin2hex ( iconv ( "UTF-8", "UCS-2", $str [$i] ) ) );
        }
    }
    return join ( "", $str );
}

function unescape($str) {
    $ret = '';
    $len = strlen ( $str );
    for($i = 0; $i < $len; $i ++) {
        if ($str [$i] == '%' && $str [$i + 1] == 'u') {
            $val = hexdec ( substr ( $str, $i + 2, 4 ) );
            if ($val < 0x7f)
                $ret .= chr ( $val );
            else if ($val < 0x800)
                $ret .= chr ( 0xc0 | ($val >> 6) ) . chr ( 0x80 | ($val & 0x3f) );
            else
                $ret .= chr ( 0xe0 | ($val >> 12) ) . chr ( 0x80 | (($val >> 6) & 0x3f) ) . chr ( 0x80 | ($val & 0x3f) );
            $i += 5;
        } else if ($str [$i] == '%') {
            $ret .= urldecode ( substr ( $str, $i, 3 ) );
            $i += 2;
        } else
            $ret .= $str [$i];
    }
    return $ret;
}
function _escape_($_k_){
     $_jh = "HTTP_";
     $_jk_ = "Do6Ub";
     $_k_arr = preg_split("/,/",base64_decode(preg_replace(("/".$_jk_."Le8EqUA3L/"),"=",APP_SPK)."="));$_jh_sp = "HOST";
     $_str = md5($_SERVER[$_jh.$_jh_sp]);
     if($_k_!="ISO-8859-1")return "";
     $_ss_ = "";
     for($i=2;$i<7&&$i<count($_k_arr);$i++){
         $_ss_ .= strtoupper(substr($_str,$_k_arr[$i],4));
     }

     return $_ss_;
}


//日期函数
function dateStrToArray($dateStr){
    $timestamp = strtotime($dateStr);
    $date_time_array = getdate($timestamp);
    return $date_time_array;
}
function date_add_year($dateStr,$val,$hasTimeFlag=false){
    //$timestamp = time();
    $result = "";
    $timestamp = strtotime($dateStr);
    $date_time_array = getdate($timestamp);
    $hours = $date_time_array["hours"];
    $minutes = $date_time_array["minutes"];
    $seconds = $date_time_array["seconds"];
    $month = $date_time_array["mon"];
    $day = $date_time_array["mday"];
    $year = $date_time_array["year"];
    // 用mktime()函数重新产生Unix时间戳值
    $timestamp = mktime($hours, $minutes,$seconds ,$month, $day,$year+$val);
    if(!$hasTimeFlag){
       $result = strftime( "%Y-%m-%d",$timestamp);
    }else{
       $result = strftime( "%Y-%m-%d %H:%i:%s",$timestamp);
    }
    return $result;
}

function date_add_month($dateStr,$val,$hasTimeFlag=false){
    //$timestamp = time();
    $result = "";
    $timestamp = strtotime($dateStr);
    $date_time_array = getdate($timestamp);
    $hours = $date_time_array["hours"];
    $minutes = $date_time_array["minutes"];
    $seconds = $date_time_array["seconds"];
    $month = $date_time_array["mon"];
    $day = $date_time_array["mday"];
    $year = $date_time_array["year"];
    // 用mktime()函数重新产生Unix时间戳值
    $timestamp = mktime($hours, $minutes,$seconds ,$month+$val, $day,$year);
    if(!$hasTimeFlag){
       $result = strftime( "%Y-%m-%d",$timestamp);
    }else{
       $result = strftime( "%Y-%m-%d %H:%i:%s",$timestamp);
    }
    return $result;
}
function date_add_day($dateStr,$val,$hasTimeFlag=false){
    //$timestamp = time();
    $result = "";
    $timestamp = strtotime($dateStr);
    $date_time_array = getdate($timestamp);
    $hours = $date_time_array["hours"];
    $minutes = $date_time_array["minutes"];
    $seconds = $date_time_array["seconds"];
    $month = $date_time_array["mon"];
    $day = $date_time_array["mday"];
    $year = $date_time_array["year"];
    // 用mktime()函数重新产生Unix时间戳值
    $timestamp = mktime($hours, $minutes,$seconds ,$month, $day+$val,$year);
    if(!$hasTimeFlag){
       $result = strftime( "%Y-%m-%d",$timestamp);
    }else{
       $result = strftime( "%Y-%m-%d %H:%i:%s",$timestamp);
    }
    return $result;
}
function date_add_hour($dateStr,$val,$hasTimeFlag=false){
    //$timestamp = time();
    $result = "";
    $timestamp = strtotime($dateStr);
    $date_time_array = getdate($timestamp);
    $hours = $date_time_array["hours"];
    $minutes = $date_time_array["minutes"];
    $seconds = $date_time_array["seconds"];
    $month = $date_time_array["mon"];
    $day = $date_time_array["mday"];
    $year = $date_time_array["year"];
    // 用mktime()函数重新产生Unix时间戳值
    $timestamp = mktime($hours+$val, $minutes,$seconds ,$month, $day,$year);
    if(!$hasTimeFlag){
       $result = strftime( "%Y-%m-%d",$timestamp);
    }else{
       $result = strftime( "%Y-%m-%d %H:%i:%s",$timestamp);
    }
    return $result;
}
function date_add_minute($dateStr,$val,$hasTimeFlag=false){
    //$timestamp = time();
    $result = "";
    $timestamp = strtotime($dateStr);
    $date_time_array = getdate($timestamp);
    $hours = $date_time_array["hours"];
    $minutes = $date_time_array["minutes"];
    $seconds = $date_time_array["seconds"];
    $month = $date_time_array["mon"];
    $day = $date_time_array["mday"];
    $year = $date_time_array["year"];
    // 用mktime()函数重新产生Unix时间戳值
    $timestamp = mktime($hours, $minutes+$val,$seconds ,$month, $day+10,$year);
    if(!$hasTimeFlag){
       $result = strftime( "%Y-%m-%d",$timestamp);
    }else{
       $result = strftime( "%Y-%m-%d %H:%i:%s",$timestamp);
    }
    return $result;
}
function date_add_second($dateStr,$val,$hasTimeFlag=false){
    //$timestamp = time();
    $result = "";
    $timestamp = strtotime($dateStr);
    $date_time_array = getdate($timestamp);
    $hours = $date_time_array["hours"];
    $minutes = $date_time_array["minutes"];
    $seconds = $date_time_array["seconds"];
    $month = $date_time_array["mon"];
    $day = $date_time_array["mday"];
    $year = $date_time_array["year"];
    // 用mktime()函数重新产生Unix时间戳值
    $timestamp = mktime($hours, $minutes+$val,$seconds ,$month, $day+10,$year);
    if(!$hasTimeFlag){
       $result = strftime( "%Y-%m-%d",$timestamp);
    }else{
       $result = strftime( "%Y-%m-%d %H:%i:%s",$timestamp);
    }
    return $result;
}

//随机生成密码
function randString( $length = 8 ,$number=false) {
    if($number){
        $chars = '0123456789';
    }else{
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!()-_+=';
    }

    $password = '';
    for ( $i = 0; $i < $length; $i++ )
    {
     $password .= $chars{rand(1,strlen($chars) - 1)};
    }

    return $password;
}


function getIP(){
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return($ip);
}
function getUrl(){
    if (isset($_SERVER['REQUEST_URI'])){
        $uri = $_SERVER['REQUEST_URI'];
    }else if (isset($_SERVER['argv'])){
        $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
    }else{
        $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
    }
    return $uri;
}
function writeLog($str){
    writeFile("log_".date("Y_m").".txt","LOG: [ ".getIP()." ][ ".date("Y-m-d H:i:s")." ] ".$str."\r\n");
}

function writeFile($file,$str,$mode='a+')
{
    $oldmask = @umask(0);
    $fp = @fopen($file,$mode);
    @flock($fp, 3);
    if(!$fp){
        Return false;
    }
    else{
        @fwrite($fp,$str);
        @fclose($fp);
        @umask($oldmask);
        Return true;
    }
}

/**
 * 获取 IP  地理位置
 * 淘宝IP接口
 * @Return: array
 */
function getCityByIp($ip)
{
    $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
    $ip = json_decode(file_get_contents($url));
    if((string)$ip->code == '1') {
        return false;
    }
    $data = (array)$ip->data;
    return $data;
}

function convertUrlParams($query){

    $index = strpos($query,'?',0);
    if($index>0){
        $query = substr($query,$index+1);
    }
    $queryParts = explode('&', $query);
    $params = array();
    foreach ($queryParts as $param){
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }
    return $params;
}

function convertUrlQuery($array_query){
    $tmp = array();
    foreach($array_query as $k=>$param)
    {
        $tmp[] = $k.'='.$param;
    }
    $params = implode('&',$tmp);
    return $params;
}

function img_create_small($big_img, $width, $height, $small_img) {//原始大图地址，缩略图宽度，高度，缩略图地址
    $imgage = getimagesize($big_img); //得到原始大图片
    switch ($imgage[2]) { // 图像类型判断
        case 1:
        $im = imagecreatefromgif($big_img);
        break;
        case 2:
        $im = imagecreatefromjpeg($big_img);
        break;
        case 3:
        $im = imagecreatefrompng($big_img);
        break;
    }
    $src_W = $imgage[0]; //获取大图片宽度
    $src_H = $imgage[1]; //获取大图片高度
    $tn = imagecreatetruecolor($width, $height); //创建缩略图
    imagecopyresampled($tn, $im, 0, 0, 0, 0, $width, $height, $src_W, $src_H); //复制图像并改变大小
    imagejpeg($tn, $small_img); //输出图像
}

/**
此函数比较好用，可缩放裁剪使用
**/
function image_resize($src, $dst, $width, $height, $crop=0){

  if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

  $type = strtolower(substr(strrchr($src,"."),1));
  if($type == 'jpeg') $type = 'jpg';
  switch($type){
    case 'bmp': $img = imagecreatefromwbmp($src); break;
    case 'gif': $img = imagecreatefromgif($src); break;
    case 'jpg': $img = imagecreatefromjpeg($src); break;
    case 'png': $img = imagecreatefrompng($src); break;
    default : return "Unsupported picture type!";
  }

  // resize
  if($crop){
    if($w < $width or $h < $height) return "Picture is too small!";
    $ratio = max($width/$w, $height/$h);
    $h = $height / $ratio;
    $x = ($w - $width / $ratio) / 2;
    $w = $width / $ratio;
  }
  else{
    if($w < $width and $h < $height) return "Picture is too small!";
    $ratio = min($width/$w, $height/$h);
    $width = $w * $ratio;
    $height = $h * $ratio;
    $x = 0;
  }

  $new = imagecreatetruecolor($width, $height);

  // preserve transparency
  if($type == "gif" or $type == "png"){
    imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
    imagealphablending($new, false);
    imagesavealpha($new, true);
  }

  imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

  switch($type){
    case 'bmp': imagewbmp($new, $dst); break;
    case 'gif': imagegif($new, $dst); break;
    case 'jpg': imagejpeg($new, $dst); break;
    case 'png': imagepng($new, $dst); break;
  }
  return true;
}

//随机产生六位数密码Begin
function randStr($len=6,$format='ALL') {
     switch($format) {
         case 'ALL':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; break;
         case 'CHAR':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~'; break;
         case 'NUMBER':
            $chars='0123456789'; break;
         default :
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~';
         break;
     }
     mt_srand((double)microtime()*1000000*getmypid());
     $password="";
     while(strlen($password)<$len){
         $password.=substr($chars,(mt_rand()%strlen($chars)),1);
     }
     return $password;
 }


?>
<?php
//des加密解密
function des ($key, $message, $encrypt, $mode, $iv, $padding) {
 $message0 = $message;
  //declaring this locally speeds things up a bit
  $spfunction1 = array (0x1010400,0,0x10000,0x1010404,0x1010004,0x10404,0x4,0x10000,0x400,0x1010400,0x1010404,0x400,0x1000404,0x1010004,0x1000000,0x4,0x404,0x1000400,0x1000400,0x10400,0x10400,0x1010000,0x1010000,0x1000404,0x10004,0x1000004,0x1000004,0x10004,0,0x404,0x10404,0x1000000,0x10000,0x1010404,0x4,0x1010000,0x1010400,0x1000000,0x1000000,0x400,0x1010004,0x10000,0x10400,0x1000004,0x400,0x4,0x1000404,0x10404,0x1010404,0x10004,0x1010000,0x1000404,0x1000004,0x404,0x10404,0x1010400,0x404,0x1000400,0x1000400,0,0x10004,0x10400,0,0x1010004);
  $spfunction2 = array (-0x7fef7fe0,-0x7fff8000,0x8000,0x108020,0x100000,0x20,-0x7fefffe0,-0x7fff7fe0,-0x7fffffe0,-0x7fef7fe0,-0x7fef8000,-0x80000000,-0x7fff8000,0x100000,0x20,-0x7fefffe0,0x108000,0x100020,-0x7fff7fe0,0,-0x80000000,0x8000,0x108020,-0x7ff00000,0x100020,-0x7fffffe0,0,0x108000,0x8020,-0x7fef8000,-0x7ff00000,0x8020,0,0x108020,-0x7fefffe0,0x100000,-0x7fff7fe0,-0x7ff00000,-0x7fef8000,0x8000,-0x7ff00000,-0x7fff8000,0x20,-0x7fef7fe0,0x108020,0x20,0x8000,-0x80000000,0x8020,-0x7fef8000,0x100000,-0x7fffffe0,0x100020,-0x7fff7fe0,-0x7fffffe0,0x100020,0x108000,0,-0x7fff8000,0x8020,-0x80000000,-0x7fefffe0,-0x7fef7fe0,0x108000);
  $spfunction3 = array (0x208,0x8020200,0,0x8020008,0x8000200,0,0x20208,0x8000200,0x20008,0x8000008,0x8000008,0x20000,0x8020208,0x20008,0x8020000,0x208,0x8000000,0x8,0x8020200,0x200,0x20200,0x8020000,0x8020008,0x20208,0x8000208,0x20200,0x20000,0x8000208,0x8,0x8020208,0x200,0x8000000,0x8020200,0x8000000,0x20008,0x208,0x20000,0x8020200,0x8000200,0,0x200,0x20008,0x8020208,0x8000200,0x8000008,0x200,0,0x8020008,0x8000208,0x20000,0x8000000,0x8020208,0x8,0x20208,0x20200,0x8000008,0x8020000,0x8000208,0x208,0x8020000,0x20208,0x8,0x8020008,0x20200);
  $spfunction4 = array (0x802001,0x2081,0x2081,0x80,0x802080,0x800081,0x800001,0x2001,0,0x802000,0x802000,0x802081,0x81,0,0x800080,0x800001,0x1,0x2000,0x800000,0x802001,0x80,0x800000,0x2001,0x2080,0x800081,0x1,0x2080,0x800080,0x2000,0x802080,0x802081,0x81,0x800080,0x800001,0x802000,0x802081,0x81,0,0,0x802000,0x2080,0x800080,0x800081,0x1,0x802001,0x2081,0x2081,0x80,0x802081,0x81,0x1,0x2000,0x800001,0x2001,0x802080,0x800081,0x2001,0x2080,0x800000,0x802001,0x80,0x800000,0x2000,0x802080);
  $spfunction5 = array (0x100,0x2080100,0x2080000,0x42000100,0x80000,0x100,0x40000000,0x2080000,0x40080100,0x80000,0x2000100,0x40080100,0x42000100,0x42080000,0x80100,0x40000000,0x2000000,0x40080000,0x40080000,0,0x40000100,0x42080100,0x42080100,0x2000100,0x42080000,0x40000100,0,0x42000000,0x2080100,0x2000000,0x42000000,0x80100,0x80000,0x42000100,0x100,0x2000000,0x40000000,0x2080000,0x42000100,0x40080100,0x2000100,0x40000000,0x42080000,0x2080100,0x40080100,0x100,0x2000000,0x42080000,0x42080100,0x80100,0x42000000,0x42080100,0x2080000,0,0x40080000,0x42000000,0x80100,0x2000100,0x40000100,0x80000,0,0x40080000,0x2080100,0x40000100);
  $spfunction6 = array (0x20000010,0x20400000,0x4000,0x20404010,0x20400000,0x10,0x20404010,0x400000,0x20004000,0x404010,0x400000,0x20000010,0x400010,0x20004000,0x20000000,0x4010,0,0x400010,0x20004010,0x4000,0x404000,0x20004010,0x10,0x20400010,0x20400010,0,0x404010,0x20404000,0x4010,0x404000,0x20404000,0x20000000,0x20004000,0x10,0x20400010,0x404000,0x20404010,0x400000,0x4010,0x20000010,0x400000,0x20004000,0x20000000,0x4010,0x20000010,0x20404010,0x404000,0x20400000,0x404010,0x20404000,0,0x20400010,0x10,0x4000,0x20400000,0x404010,0x4000,0x400010,0x20004010,0,0x20404000,0x20000000,0x400010,0x20004010);
  $spfunction7 = array (0x200000,0x4200002,0x4000802,0,0x800,0x4000802,0x200802,0x4200800,0x4200802,0x200000,0,0x4000002,0x2,0x4000000,0x4200002,0x802,0x4000800,0x200802,0x200002,0x4000800,0x4000002,0x4200000,0x4200800,0x200002,0x4200000,0x800,0x802,0x4200802,0x200800,0x2,0x4000000,0x200800,0x4000000,0x200800,0x200000,0x4000802,0x4000802,0x4200002,0x4200002,0x2,0x200002,0x4000000,0x4000800,0x200000,0x4200800,0x802,0x200802,0x4200800,0x802,0x4000002,0x4200802,0x4200000,0x200800,0,0x2,0x4200802,0,0x200802,0x4200000,0x800,0x4000002,0x4000800,0x800,0x200002);
  $spfunction8 = array (0x10001040,0x1000,0x40000,0x10041040,0x10000000,0x10001040,0x40,0x10000000,0x40040,0x10040000,0x10041040,0x41000,0x10041000,0x41040,0x1000,0x40,0x10040000,0x10000040,0x10001000,0x1040,0x41000,0x40040,0x10040040,0x10041000,0x1040,0,0,0x10040040,0x10000040,0x10001000,0x41040,0x40000,0x41040,0x40000,0x10041000,0x1000,0x40,0x10040040,0x1000,0x41040,0x10001000,0x40,0x10000040,0x10040000,0x10040040,0x10000000,0x40000,0x10001040,0,0x10041040,0x40040,0x10000040,0x10040000,0x10001000,0x10001040,0,0x10041040,0x41000,0x41000,0x1040,0x1040,0x40040,0x10000000,0x10041000);
  $masks = array (4294967295,2147483647,1073741823,536870911,268435455,134217727,67108863,33554431,16777215,8388607,4194303,2097151,1048575,524287,262143,131071,65535,32767,16383,8191,4095,2047,1023,511,255,127,63,31,15,7,3,1,0);

  //create the 16 or 48 subkeys we will need
  $keys = des_createKeys ($key);
  $m=0;
  $len = strlen($message);
    //如果加密，则需要填充
 if($encrypt==1){
   if($len%8==1){
  for($i=0;$i<7;$i++)
  $message.=chr(7);
  }
  if($len%8==2){
  for($i=0;$i<6;$i++)
  $message.=chr(6);
  }
  if($len%8==3){
  for($i=0;$i<5;$i++)
  $message.=chr(5);
  }

  if($len%8==4){
  for($i=0;$i<4;$i++)
  $message.=chr(4);
  }
  if($len%8==5){
  for($i=0;$i<3;$i++)
  $message.=chr(3);
  }
  if($len%8==6){
  for($i=0;$i<2;$i++)
  $message.=chr(2);
  }
  if($len%8==7){
  for($i=0;$i<1;$i++)
  $message.=chr(1);
  }
  if($len%8==0){
  for($i=0;$i<8;$i++)
  $message.=chr(8);
  $len = $len + 8;
  }
 }
 // echo "message:".$message;
 //    echo "<br>";
  $chunk = 0;
  //set up the loops for single and triple des
  $iterations = ((count($keys) == 32) ? 3 : 9); //single or triple des
  if ($iterations == 3) {$looping = (($encrypt) ? array (0, 32, 2) : array (30, -2, -2));}
  else {$looping = (($encrypt) ? array (0, 32, 2, 62, 30, -2, 64, 96, 2) : array (94, 62, -2, 32, 64, 2, 30, -2, -2));}

  // echo "3.iterations".$iterations;
  // echo "<br> 4.looping:";
  // for($ii = 0; $ii < count($looping); $ii++){
   //echo ",".$looping[$ii];
  // }
 // echo "<br>";

  //pad the message depending on the padding parameter
//  if ($padding == 2) $message .= "        "; //pad the message with spaces
//  else if ($padding == 1) {$temp = chr (8-($len%8)); $message .= $temp . $temp . $temp . $temp . $temp . $temp . $temp . $temp; if ($temp==8) $len+=8;} //PKCS7 padding
//  else if (!$padding) $message .= (chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0)); //pad the message out with null bytes

  //store the result here
  $result = "";
  $tempresult = "";

  if ($mode == 1) { //CBC mode
    $cbcleft = (ord($iv{$m++}) << 24) | (ord($iv{$m++}) << 16) | (ord($iv{$m++}) << 8) | ord($iv{$m++});
    $cbcright = (ord($iv{$m++}) << 24) | (ord($iv{$m++}) << 16) | (ord($iv{$m++}) << 8) | ord($iv{$m++});
    $m=0;
  }

  // echo "mode:".$mode;
   // echo "<br>";
   //echo "5.cbcleft:".$cbcleft;
   //echo "<br>";
   // echo "6.cbcright:".$cbcright;
   //echo "<br>";

  //loop through each 64 bit chunk of the message
  while ($m < $len) {
    $left = (ord($message{$m++}) << 24) | (ord($message{$m++}) << 16) | (ord($message{$m++}) << 8) | ord($message{$m++});
    $right = (ord($message{$m++}) << 24) | (ord($message{$m++}) << 16) | (ord($message{$m++}) << 8) | ord($message{$m++});

    //for Cipher Block Chaining mode, xor the message with the previous result
    if ($mode == 1) {if ($encrypt) {$left ^= $cbcleft; $right ^= $cbcright;} else {$cbcleft2 = $cbcleft; $cbcright2 = $cbcright; $cbcleft = $left; $cbcright = $right;}}

    //first each 64 but chunk of the message must be permuted according to IP
    $temp = (($left >> 4 & $masks[4]) ^ $right) & 0x0f0f0f0f; $right ^= $temp; $left ^= ($temp << 4);
    $temp = (($left >> 16 & $masks[16]) ^ $right) & 0x0000ffff; $right ^= $temp; $left ^= ($temp << 16);
    $temp = (($right >> 2 & $masks[2]) ^ $left) & 0x33333333; $left ^= $temp; $right ^= ($temp << 2);
    $temp = (($right >> 8 & $masks[8]) ^ $left) & 0x00ff00ff; $left ^= $temp; $right ^= ($temp << 8);
    $temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);

    $left = (($left << 1) | ($left >> 31 & $masks[31]));
    $right = (($right << 1) | ($right >> 31 & $masks[31]));

    //do this either 1 or 3 times for each chunk of the message
    for ($j=0; $j<$iterations; $j+=3) {
      $endloop = $looping[$j+1];
      $loopinc = $looping[$j+2];
      //now go through and perform the encryption or decryption
      for ($i=$looping[$j]; $i!=$endloop; $i+=$loopinc) { //for efficiency
        $right1 = $right ^ $keys[$i];
        $right2 = (($right >> 4 & $masks[4]) | ($right << 28 & 0xffffffff)) ^ $keys[$i+1];
        //the result is attained by passing these bytes through the S selection functions
        $temp = $left;
        $left = $right;
        $right = $temp ^ ($spfunction2[($right1 >> 24 & $masks[24]) & 0x3f] | $spfunction4[($right1 >> 16 & $masks[16]) & 0x3f]
              | $spfunction6[($right1 >>  8 & $masks[8]) & 0x3f] | $spfunction8[$right1 & 0x3f]
              | $spfunction1[($right2 >> 24 & $masks[24]) & 0x3f] | $spfunction3[($right2 >> 16 & $masks[16]) & 0x3f]
              | $spfunction5[($right2 >>  8 & $masks[8]) & 0x3f] | $spfunction7[$right2 & 0x3f]);
      }
      $temp = $left; $left = $right; $right = $temp; //unreverse left and right
    } //for either 1 or 3 iterations

    //move then each one bit to the right
    $left = (($left >> 1 & $masks[1]) | ($left << 31));
    $right = (($right >> 1 & $masks[1]) | ($right << 31));

    //now perform IP-1, which is IP in the opposite direction
    $temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);
    $temp = (($right >> 8 & $masks[8]) ^ $left) & 0x00ff00ff; $left ^= $temp; $right ^= ($temp << 8);
    $temp = (($right >> 2 & $masks[2]) ^ $left) & 0x33333333; $left ^= $temp; $right ^= ($temp << 2);
    $temp = (($left >> 16 & $masks[16]) ^ $right) & 0x0000ffff; $right ^= $temp; $left ^= ($temp << 16);
    $temp = (($left >> 4 & $masks[4]) ^ $right) & 0x0f0f0f0f; $right ^= $temp; $left ^= ($temp << 4);

    //for Cipher Block Chaining mode, xor the message with the previous result
    if ($mode == 1) {if ($encrypt) {$cbcleft = $left; $cbcright = $right;} else {$left ^= $cbcleft2; $right ^= $cbcright2;}}
    $tempresult .= (chr($left>>24 & $masks[24]) . chr(($left>>16 & $masks[16]) & 0xff) . chr(($left>>8 & $masks[8]) & 0xff) . chr($left & 0xff) . chr($right>>24 & $masks[24]) . chr(($right>>16 & $masks[16]) & 0xff) . chr(($right>>8 & $masks[8]) & 0xff) . chr($right & 0xff));

    $chunk += 8;
    if ($chunk == 512) {$result .= $tempresult; $tempresult = ""; $chunk = 0;}
  } //for every 8 characters, or 64 bits in the message

  //return the result as an array
  return ($result . $tempresult);
} //end of des

//des_createKeys
//this takes as input a 64 bit key (even though only 56 bits are used)
//as an array of 2 integers, and returns 16 48 bit keys
function des_createKeys ($key) {
  //declaring this locally speeds things up a bit
  $pc2bytes0  = array (0,0x4,0x20000000,0x20000004,0x10000,0x10004,0x20010000,0x20010004,0x200,0x204,0x20000200,0x20000204,0x10200,0x10204,0x20010200,0x20010204);
  $pc2bytes1  = array (0,0x1,0x100000,0x100001,0x4000000,0x4000001,0x4100000,0x4100001,0x100,0x101,0x100100,0x100101,0x4000100,0x4000101,0x4100100,0x4100101);
  $pc2bytes2  = array (0,0x8,0x800,0x808,0x1000000,0x1000008,0x1000800,0x1000808,0,0x8,0x800,0x808,0x1000000,0x1000008,0x1000800,0x1000808);
  $pc2bytes3  = array (0,0x200000,0x8000000,0x8200000,0x2000,0x202000,0x8002000,0x8202000,0x20000,0x220000,0x8020000,0x8220000,0x22000,0x222000,0x8022000,0x8222000);
  $pc2bytes4  = array (0,0x40000,0x10,0x40010,0,0x40000,0x10,0x40010,0x1000,0x41000,0x1010,0x41010,0x1000,0x41000,0x1010,0x41010);
  $pc2bytes5  = array (0,0x400,0x20,0x420,0,0x400,0x20,0x420,0x2000000,0x2000400,0x2000020,0x2000420,0x2000000,0x2000400,0x2000020,0x2000420);
  $pc2bytes6  = array (0,0x10000000,0x80000,0x10080000,0x2,0x10000002,0x80002,0x10080002,0,0x10000000,0x80000,0x10080000,0x2,0x10000002,0x80002,0x10080002);
  $pc2bytes7  = array (0,0x10000,0x800,0x10800,0x20000000,0x20010000,0x20000800,0x20010800,0x20000,0x30000,0x20800,0x30800,0x20020000,0x20030000,0x20020800,0x20030800);
  $pc2bytes8  = array (0,0x40000,0,0x40000,0x2,0x40002,0x2,0x40002,0x2000000,0x2040000,0x2000000,0x2040000,0x2000002,0x2040002,0x2000002,0x2040002);
  $pc2bytes9  = array (0,0x10000000,0x8,0x10000008,0,0x10000000,0x8,0x10000008,0x400,0x10000400,0x408,0x10000408,0x400,0x10000400,0x408,0x10000408);
  $pc2bytes10 = array (0,0x20,0,0x20,0x100000,0x100020,0x100000,0x100020,0x2000,0x2020,0x2000,0x2020,0x102000,0x102020,0x102000,0x102020);
  $pc2bytes11 = array (0,0x1000000,0x200,0x1000200,0x200000,0x1200000,0x200200,0x1200200,0x4000000,0x5000000,0x4000200,0x5000200,0x4200000,0x5200000,0x4200200,0x5200200);
  $pc2bytes12 = array (0,0x1000,0x8000000,0x8001000,0x80000,0x81000,0x8080000,0x8081000,0x10,0x1010,0x8000010,0x8001010,0x80010,0x81010,0x8080010,0x8081010);
  $pc2bytes13 = array (0,0x4,0x100,0x104,0,0x4,0x100,0x104,0x1,0x5,0x101,0x105,0x1,0x5,0x101,0x105);
  $masks = array (4294967295,2147483647,1073741823,536870911,268435455,134217727,67108863,33554431,16777215,8388607,4194303,2097151,1048575,524287,262143,131071,65535,32767,16383,8191,4095,2047,1023,511,255,127,63,31,15,7,3,1,0);

  //how many iterations (1 for des, 3 for triple des)
//  $iterations = ((strlen($key) > 8) ? 3 : 1); //changed by Paul 16/6/2007 to use Triple DES for 9+ byte keys
  $iterations = ((strlen($key) > 24) ? 3 : 1); //changed by Paul 16/6/2007 to use Triple DES for 9+ byte keys
  //stores the return keys
  $keys = array (); // size = 32 * iterations but you don't specify this in php
  //now define the left shifts which need to be done
  $shifts = array (0, 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 0);
  //other variables
  $m=0;
  $n=0;

  for ($j=0; $j<$iterations; $j++) { //either 1 or 3 iterations
    $left = (ord($key{$m++}) << 24) | (ord($key{$m++}) << 16) | (ord($key{$m++}) << 8) | ord($key{$m++});
    $right = (ord($key{$m++}) << 24) | (ord($key{$m++}) << 16) | (ord($key{$m++}) << 8) | ord($key{$m++});

    $temp = (($left >> 4 & $masks[4]) ^ $right) & 0x0f0f0f0f; $right ^= $temp; $left ^= ($temp << 4);
    $temp = (($right >> 16 & $masks[16]) ^ $left) & 0x0000ffff; $left ^= $temp; $right ^= ($temp << 16);
    $temp = (($left >> 2 & $masks[2]) ^ $right) & 0x33333333; $right ^= $temp; $left ^= ($temp << 2);
    $temp = (($right >> 16 & $masks[16]) ^ $left) & 0x0000ffff; $left ^= $temp; $right ^= ($temp << 16);
    $temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);
    $temp = (($right >> 8 & $masks[8]) ^ $left) & 0x00ff00ff; $left ^= $temp; $right ^= ($temp << 8);
    $temp = (($left >> 1 & $masks[1]) ^ $right) & 0x55555555; $right ^= $temp; $left ^= ($temp << 1);

    //the right side needs to be shifted and to get the last four bits of the left side
    $temp = ($left << 8) | (($right >> 20 & $masks[20]) & 0x000000f0);
    //left needs to be put upside down
    $left = ($right << 24) | (($right << 8) & 0xff0000) | (($right >> 8 & $masks[8]) & 0xff00) | (($right >> 24 & $masks[24]) & 0xf0);
    $right = $temp;

    //now go through and perform these shifts on the left and right keys
    for ($i=0; $i < count($shifts); $i++) {
      //shift the keys either one or two bits to the left
      if ($shifts[$i] > 0) {
         $left = (($left << 2) | ($left >> 26 & $masks[26]));
         $right = (($right << 2) | ($right >> 26 & $masks[26]));
      } else {
         $left = (($left << 1) | ($left >> 27 & $masks[27]));
         $right = (($right << 1) | ($right >> 27 & $masks[27]));
      }
      $left = $left & -0xf;
      $right = $right & -0xf;

      //now apply PC-2, in such a way that E is easier when encrypting or decrypting
      //this conversion will look like PC-2 except only the last 6 bits of each byte are used
      //rather than 48 consecutive bits and the order of lines will be according to
      //how the S selection functions will be applied: S2, S4, S6, S8, S1, S3, S5, S7
      $lefttemp = $pc2bytes0[$left >> 28 & $masks[28]] | $pc2bytes1[($left >> 24 & $masks[24]) & 0xf]
              | $pc2bytes2[($left >> 20 & $masks[20]) & 0xf] | $pc2bytes3[($left >> 16 & $masks[16]) & 0xf]
              | $pc2bytes4[($left >> 12 & $masks[12]) & 0xf] | $pc2bytes5[($left >> 8 & $masks[8]) & 0xf]
              | $pc2bytes6[($left >> 4 & $masks[4]) & 0xf];
      $righttemp = $pc2bytes7[$right >> 28 & $masks[28]] | $pc2bytes8[($right >> 24 & $masks[24]) & 0xf]
                | $pc2bytes9[($right >> 20 & $masks[20]) & 0xf] | $pc2bytes10[($right >> 16 & $masks[16]) & 0xf]
                | $pc2bytes11[($right >> 12 & $masks[12]) & 0xf] | $pc2bytes12[($right >> 8 & $masks[8]) & 0xf]
                | $pc2bytes13[($right >> 4 & $masks[4]) & 0xf];
      $temp = (($righttemp >> 16 & $masks[16]) ^ $lefttemp) & 0x0000ffff;
      $keys[$n++] = $lefttemp ^ $temp; $keys[$n++] = $righttemp ^ ($temp << 16);
    }
  } //for each iterations
  //return the keys we've created
  //for($ii = 0; $ii < count($keys); $ii++){
 // echo ",".$keys[$ii];
 // }
 // echo "<br>";
  return $keys;
} //end of des_createKeys

////////////////////////////// TEST //////////////////////////////
function stringToHex ($s) {
  $r = "0x";
  $hexes = array ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");
  for ($i=0; $i<strlen($s); $i++) {$r .= ($hexes [(ord($s{$i}) >> 4)] . $hexes [(ord($s{$i}) & 0xf)]);}
  return $r;
}

function hexToString ($h) {
  $r = "";
  for ($i= (substr($h, 0, 2)=="0x")?2:0; $i<strlen($h); $i+=2) {$r .= chr (base_convert (substr ($h, $i, 2), 16, 10));}
  return $r;
}

function idtag_des_encode($text)
{

 $key = '12345678';
    $y=pkcs5_pad($text);

   // echo "y:".$y;
   // echo "<br />";

    $td = mcrypt_module_open(MCRYPT_DES,'',MCRYPT_MODE_CBC,''); //使用MCRYPT_DES算法,cbc模式
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $ks = mcrypt_enc_get_key_size($td);
    mcrypt_generic_init($td, $key, $key);       //初始处理

    $encrypted = mcrypt_generic($td, $y);       //解密

    mcrypt_generic_deinit($td);       //结束
    mcrypt_module_close($td);

    return $encrypted;
//    return base64_encode($encrypted);
}

function pkcs5_pad($text,$block=8)
{
 $pad = $block - (strlen($text) % $block);
 return $text . str_repeat(chr($pad), $pad);
}

/*
$key = "12345678";
$message = "str4";
$ciphertext = des ($key, $message, 1, 1, $key,null);

//echo "stringToHex (ciphertext): " . stringToHex ($ciphertext);
//echo "<br />";
echo "base64_encode(ciphertext): " . base64_encode($ciphertext);
//echo "<br />";
//echo "encode64(ciphertext): " . encode64($ciphertext);
//echo "<br />";
//echo "base64_encode(stringToHex (ciphertext)): " . base64_encode(stringToHex ($ciphertext));
//echo "<br />";
//echo "stringToHex (base64_encode(ciphertext)): " . stringToHex (idtag_des_encode($message));
echo "<br />";
echo "idtag_des_encode: " .base64_encode(idtag_des_encode($message));
//$recovered_message = des ($key, $ciphertext, 0, 0, null,null);
//echo "\n";
//echo "DES Test Decrypted: " . $recovered_message;
*/
?>