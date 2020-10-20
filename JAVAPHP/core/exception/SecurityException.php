<?php
class SecurityException {
    
    public $var;
    
    const THROW_NONE    = 0;  //没有错误
    const THROW_NO_LOGIN = 1;   //没有登陆
    const THROW_NO_PERMISSION  = 2;  //没有权限
    
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($avalue = self::THROW_NONE) {
        // 自定义的代码
        switch ($avalue) {
            case self::THROW_NO_LOGIN:
                // 抛出自定义异常
                throw new BaseException('非法操作，您尚未登陆！', 0x2001);
                break;

            case self::THROW_NO_PERMISSION:
                // 抛出默认的异常
                throw new BaseException('非法操作，您没有权限！', 0x2002);   
                break;

            default:
                // 没有异常的情况下，创建一个对象
                $this->var = $avalue;
                break;
        }
    }



}



?>
