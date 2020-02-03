<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 21:04
 */
namespace app\common\lib\exception;
use  think\Exception;


class ApiException extends Exception{
    public $message = '';
    public $httpCode = 500;
    public $code = 0;
    /**
     * @param string $message
     * @param int $httpCode
     * @param int $code
     */
    public  function  _construct($message = '',$httpCode = 0,$code = 0){
        $this->httpCode = $httpCode;
        $this->message = $message;
        $this->code = $code;
    }
}