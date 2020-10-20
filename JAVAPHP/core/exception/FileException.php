<?php
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
class FileException {
    
    public $var;
    
    const THROW_NONE    = 0;  //没有错误
    const THROW_NO_FILE  = 1;  //文件不存在
    const THROW_NO_PATH = 2;   //路径不存在
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($avalue = self::THROW_NONE) {
        // 自定义的代码
        switch ($avalue) {
            case self::THROW_NO_FILE:
                // 抛出自定义异常
                throw new BaseException('文件不存在！', 0x1001);
                break;
            case self::THROW_NO_PATH:
                // 抛出默认的异常
                throw new BaseException('路径不存在！', 0x1002);
                break;
            default:
                // 没有异常的情况下，创建一个对象
                $this->var = $avalue;
                break;
        }
    }



}



?>
