<?php
/**
* 自定义一个异常处理类
*/
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
class BaseException extends Exception
{
    
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0) {
        // 确保所有变量都被正确赋值
        parent::__construct($message, $code);
    }

    // 自定义字符串输出的样式 */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

//输出错误信息
function showError($e){
     echo encodeUtf8("【错误代码".$e->getCode()."】".$e->getMessage());
}

?>