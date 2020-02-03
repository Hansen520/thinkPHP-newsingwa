<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 17:42
 */
namespace app\common\lib\exception;
use think\exception\Handle;
//Handle方法的重写
class ApiHandleException extends Handle{
    /*
     * http状态码
     * */
    public $httpCode = 500;
    public function render(\Exception $e)
    {
        if (config('app_debug') == true){
            return parent::render($e);
        }
        if($e instanceof ApiException){
            $this->httpCode = $e->httpCode;
        }
        return show(0,$e->getMessage(),[],$this->httpCode);
    }
}