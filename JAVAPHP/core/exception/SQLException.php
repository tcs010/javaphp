<?php
if (!defined("TCS_APP")){
    exit( "Access Denied" );
}
class SQLException {
    
    public $var;
    
    const THROW_NONE    = 0;  //没有错误
    const THROW_CONN_ERROR  = 1;  //文件不存在
    const THROW_DB_ERROR = 2;   //打开数据库出错
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($avalue = self::THROW_NONE) {
        // 自定义的代码
        switch ($avalue) {
            case self::THROW_CONN_ERROR:
                // 抛出自定义异常
                throw new BaseException('无法连接数据库服务器！', 0x2001);
                break;

            case self::THROW_DB_ERROR:
                // 抛出默认的异常
                throw new BaseException('无法打开数据库！', 0x2001);
                break;

            default:
                // 没有异常的情况下，创建一个对象
                $this->var = $avalue;
                break;
        }
    }



}



?>
