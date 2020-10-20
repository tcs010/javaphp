<?php
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
/*
* @(#)UploadFile.php (beta) 2005/2/19
*
* exBlog上传附件类，可同时处理用户多个上传文件。效验文件有效性后存储至指定目录。
* 可返回上传文件的相关有用信息供其它程序使用。（如文件名、类型、大小、保存路径）
* 使用方法请见本类底部（UploadFile类使用注释）信息。
*/
class UploadFile {

var $user_post_file = array(); //用户上传的文件
var $save_file_path;    //存放用户上传文件的路径
var $max_file_size;     //单位M 文件最大尺寸 2097152=2M 1048576=1M
var $last_error;     //记录最后一次出错信息
//默认允许用户上传的文件类型
var $allow_type = array('gif', 'jpg', 'png', 'zip', 'rar', 'txt', 'doc', 'docx', 'pdf','xls','xlsx','flv');
var $final_file_path; //最终保存的文件名

var $save_info = array(); //返回一组有用信息，用于提示用户。

/**
* 构造函数，用与初始化相关信息，用户待上传文件、存储路径等
*
* @param Array $file 用户上传的文件
* @param String $path 存储用户上传文件的路径
* @param Integer $size 允许用户上传文件的大小(M)
* @param Array $type   此数组中存放允计用户上传的文件类型  $type = array('gif', 'jpg', 'png', 'zip', 'rar');
*/
function UploadFile($file, $path, $dateFolder=true, $size = 500, $type = '') {
    $this->user_post_file = $file;
    if($dateFolder==true){
        $path = $path.'/day_'.date('ymd'); 
        if(!is_dir($path)){
            @mkdir($path, 0777);
            @fclose(fopen($path.'/index.htm', 'w'));
        }
    }
    
    $this->save_file_path = $path;
    $this->max_file_size = $size*1048576; //如果用户不填写文件大小，则默认为2M=2*1048576.
    if ($type != ''){
       $this->allow_type = $type;
    }
}


/**
* 存储用户上传文件，检验合法性通过后，存储至指定位置。
* @access public
* @return int    值为0时上传失败，非0表示上传成功的个数。
*/
function upload() {
    for ($i = 0; $i < count($this->user_post_file['name']); $i++) {
       //如果当前文件上传功能，则执行下一步。
       if ($this->user_post_file['error'][$i] == 0) {
       //取当前文件名、临时文件名、大小、扩展名，后面将用到。
       $name = $this->user_post_file['name'][$i];
       $tmpname = $this->user_post_file['tmp_name'][$i];
       $size = $this->user_post_file['size'][$i];
       $mime_type = $this->user_post_file['type'][$i];
       $type = $this->getFileExt($this->user_post_file['name'][$i]);

       //检测当前上传文件大小是否合法。
       if (!$this->checkSize($size)) {
           $this->last_error = "The file size is too big. File name is: ".$name;
           $this->halt($this->last_error);
         continue;
       }
        //检测当前上传文件扩展名是否合法。
       if (!$this->checkType($type)) {
         $this->last_error = "Unallowable file type: .".$type." File name is: ".$name;
         $this->halt($this->last_error);
         continue;
        }
        //检测当前上传文件是否非法提交。
        if(!is_uploaded_file($tmpname)) {
         $this->last_error = "Invalid post file method. File name is: ".$name;
         $this->halt($this->last_error);
         continue;
        }
        //移动文件后，重命名文件用。
        $basename = $this->getBaseName($name, ".".$type);
        //移动后的文件名
        //$saveas = $basename."-".time().".".$type;
        $saveas = date("YmdHis").".".$type;
        //组合新文件名再存到指定目录下，格式：存储路径 + 文件名 + 时间 + 扩展名
        $this->final_file_path = $this->save_file_path."/".$saveas;
        
        
        if(!move_uploaded_file($tmpname, $this->final_file_path)) {
         $this->last_error = $this->user_post_file['error'][$i];
         $this->halt($this->last_error);
         continue;
        }
        //添加水印部分
        if(APP_WATERMARK==1){
            $waterPos = 9;
            imageWaterMark($this->final_file_path,$waterPos,APP_WATERMARK_IMAGE,"",$textFont=5,$textColor="#FF0000");
        }else if(APP_WATERMARK==2){
            $waterPos = 5;
            imageWaterMark($this->final_file_path,$waterPos,"",APP_WATERMARK_TEXT,$textFont=5,$textColor="#FF0000");
        }

        //存储当前文件的有关信息，以便其它程序调用。
        $this->save_info[] = array("name" => $name, "type" => $type,
               "mime_type" => $mime_type,
                                 "size" => $size, "saveas" => $saveas,
                                 "path" => $this->final_file_path);
       }
    }
    return count($this->save_info); //返回上传成功的文件数目
}

    /**
    * 返回一些有用的信息，以便用于其它地方。
    * @access public
    * @return Array 返回最终保存的路径
    */
    function getSaveInfo() {
        return $this->save_info;
    }

    /**
    * 检测用户提交文件大小是否合法
    * @param Integer $size 用户上传文件的大小
    * @access private
    * @return boolean 如果为true说明大小合法，反之不合法
    */
    function checkSize($size) {
        if ($size > $this->max_file_size) {
           return false;
        }
        else {
           return true;
        }
    }

    /**
    * 检测用户提交文件类型是否合法
    * @access private
    * @return boolean 如果为true说明类型合法，反之不合法
    */
    function checkType($extension) {
        foreach ($this->allow_type as $type) {
           if (strcasecmp($extension , $type) == 0)
            return true;
        }
        return false;
    }

    /**
    * 显示出错信息
    * @param $msg    要显示的出错信息    
    * @access private
    */
    function halt($msg) {
        printf("<b><UploadFile Error:></b> %s <br>\n", $msg);
    }

    /**
    * 取文件扩展名
    * @param String $filename 给定要取扩展名的文件
    * @access private
    * @return String      返回给定文件扩展名
    */
    function getFileExt($filename) {
    $stuff = pathinfo($filename);
    return $stuff['extension'];
    }
    /**
    * 取给定文件文件名，不包括扩展名。
    * eg: getBaseName("j:/hexuzhong.jpg"); //返回 hexuzhong
    *
    * @param String $filename 给定要取文件名的文件
    * @access private
    * @return String 返回文件名
    */
    function getBaseName($filename, $type) {
    $basename = basename($filename, $type);
    return $basename;
    }
}
//html调用的上传文件函数
//$uploadFilesArr    <input type="file" name="file[]">
/*
function uploadFiles($filepath,$uploadFilesArr,$size="",$type=""){
    //上传文件部分
        $fileUploadPath=$filepath;
        $fileUploadPath = $fileUploadPath.'/day_'.date('ymd'); 
        if(!is_dir($fileUploadPath)){
            @mkdir($fileUploadPath, 0777);
            @fclose(fopen($fileUploadPath.'/index.htm', 'w'));
        }    
        //设置允许用户上传的文件类型。
        //$type = array('gif', 'jpg', 'png', 'zip', 'rar');
        //实例化上传类，第一个参数为用户上传的文件组、第二个参数为存储路径、
        //第三个参数为文件最大大小。如果不填则默认为2M
        //第四个参数为充许用户上传的类型数组。如果不填则默认为gif, jpg, png, zip, rar, txt, doc, pdf
        
        $upload = new UploadFile($uploadFilesArr, $fileUploadPath, 500000, $type);
        //上传用户文件，返回int值，为上传成功的文件个数。
        $num = $upload->upload();
        if ($num != 0) {
            //echo "上传成功<br>";
            $uploadArray=$upload->getSaveInfo();
        } 
        return $uploadArray ;   
}
*/

//水印图片
function imageWaterMark($groundImage,$waterPos=0,$waterImage="",$waterText="",$textFont=5,$textColor="#FF0000"){ 
    $isWaterImage = FALSE; 
    $formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。"; 

    //读取水印文件 
    if(!empty($waterImage) && file_exists($waterImage)) 
    { 
        $isWaterImage = TRUE; 
        $water_info = getimagesize($waterImage); 
        $water_w    = $water_info[0];//取得水印图片的宽 
        $water_h    = $water_info[1];//取得水印图片的高 

        switch($water_info[2])//取得水印图片的格式 
        { 
            case 1:$water_im = imagecreatefromgif($waterImage);break; 
            case 2:$water_im = imagecreatefromjpeg($waterImage);break; 
            case 3:$water_im = imagecreatefrompng($waterImage);break; 
            default:die($formatMsg); 
        } 
    } 

    //读取背景图片 
    if(!empty($groundImage) && file_exists($groundImage)) 
    { 
        $ground_info = getimagesize($groundImage); 
        $ground_w    = $ground_info[0];//取得背景图片的宽 
        $ground_h    = $ground_info[1];//取得背景图片的高 

        switch($ground_info[2])//取得背景图片的格式 
        { 
            case 1:$ground_im = imagecreatefromgif($groundImage);break; 
            case 2:$ground_im = imagecreatefromjpeg($groundImage);break; 
            case 3:$ground_im = imagecreatefrompng($groundImage);break; 
            default:die($formatMsg); 
        } 
    } 
    else 
    { 
        die("需要加水印的图片不存在！"); 
    } 

    //水印位置 
    if($isWaterImage)//图片水印 
    { 
        $w = $water_w; 
        $h = $water_h; 
        $label = "图片的"; 
    } 
    else//文字水印 
    { 
        echo dirname(__FILE__);
        //$waterText = iconv('GB2312','UTF-8',$waterText); 
        $font = "./fonts/cour.ttf";
        //$font = "c:/windows/fonts/arial.ttf";
        $temp = imagettfbbox(ceil($textFont*2.5),0,$font,$waterText);//取得使用 TrueType 字体的文本的范围 
        $w = $temp[2] - $temp[6]; 
        $h = $temp[3] - $temp[7]; 
        unset($temp); 
        $label = "文字区域"; 
    } 
    if( ($ground_w<$w) || ($ground_h<$h) ) 
    { 
        echo "需要加水印的图片的长度或宽度比水印".$label."还小，无法生成水印！"; 
        return; 
    } 
    switch($waterPos) 
    { 
        case 0://随机 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break; 
        case 1://1为顶端居左 
            $posX = 0; 
            $posY = 0; 
            break; 
        case 2://2为顶端居中 
            $posX = ($ground_w - $w) / 2; 
            $posY = 0; 
            break; 
        case 3://3为顶端居右 
            $posX = $ground_w - $w; 
            $posY = 0; 
            break; 
        case 4://4为中部居左 
            $posX = 0; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 5://5为中部居中 
            $posX = ($ground_w - $w) / 2; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 6://6为中部居右 
            $posX = $ground_w - $w; 
            $posY = ($ground_h - $h) / 2; 
            break; 
        case 7://7为底端居左 
            $posX = 0; 
            $posY = $ground_h - $h; 
            break; 
        case 8://8为底端居中 
            $posX = ($ground_w - $w) / 2; 
            $posY = $ground_h - $h; 
            break; 
        case 9://9为底端居右 
            $posX = $ground_w - $w; 
            $posY = $ground_h - $h; 
            break; 
        default://随机 
            $posX = rand(0,($ground_w - $w)); 
            $posY = rand(0,($ground_h - $h)); 
            break;     
    } 

    //设定图像的混色模式 
    imagealphablending($ground_im, true); 

    if($isWaterImage)//图片水印 
    { 
        imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h);//拷贝水印到目标文件         
    } 
    else//文字水印 
    { 
        if( !empty($textColor) && (strlen($textColor)==7) ) 
        { 
            $R = hexdec(substr($textColor,1,2)); 
            $G = hexdec(substr($textColor,3,2)); 
            $B = hexdec(substr($textColor,5)); 
        } 
        else 
        { 
            die("水印文字颜色格式不正确！"); 
        } 
        //$str = iconv('UTF-8','GB2312',$waterText); 
        imagestring ( $ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B));         
    } 

    //生成水印后的图片 
    @unlink($groundImage); 
    switch($ground_info[2])//取得背景图片的格式 
    { 
        case 1:imagegif($ground_im,$groundImage);break; 
        case 2:imagejpeg($ground_im,$groundImage);break; 
        case 3:imagepng($ground_im,$groundImage);break; 
        default:die($errorMsg); 
    } 

    //释放内存 
    if(isset($water_info)) unset($water_info); 
    if(isset($water_im)) imagedestroy($water_im); 
    unset($ground_info); 
    imagedestroy($ground_im); 
}

?>