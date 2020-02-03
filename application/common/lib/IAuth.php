<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/2
 * Time: 21:11
 */

namespace app\common\lib;
use app\common\lib\Aes;
use think\Cache;


class IAuth
{
    public static function setPassword($data){
        //config也是tp5的一个方法
        return md5($data.config('app.password_pre_halt'));
    }

    /**
     * 生成每次请求的sign
     * @param array $data
     */
    public static function setSign($data = [])
    {
        //1.按字段排序
        ksort($data);
        //2.拼接字符串数据
        $string = http_build_query($data);
        //3通过aes来加密
        $string = (new Aes())->encrypt($string);
        //4.所有字符串转化大写
        //$string = strtoupper($string);
        return $string;
    }

    public static function checkSignPass($data)
    {
        $str = (new Aes())->decrypt($data['sign']);
        if(empty($str)){
            return false;
        }
        parse_str($str,$arr);
        if(!is_array($arr) || empty($arr['did'])||$arr['did'] != $data['did']){
            return false;
        }
        if(!config('app_debug')){
            if((time() - ceil($arr['time'] / 1000)) > config('app.app_sign_time')){
                return false;
            }
            //唯一性判定
/*            if(Cache::get($data['sign'])){
                return false;
            }
            return true;*/
        }
    }
    public static function setAppLoginToken($phone = ''){
        $str = md5(uniqid(md5(microtime(true)),true));
        $str = sha1($str.$phone);
        return $str;
    }
}