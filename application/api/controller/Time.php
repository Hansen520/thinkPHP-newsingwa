<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 16:09
 */

namespace app\api\controller;
use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;


class Time extends Controller {
   public function index(){
       return show(1,'OK',time());
   }

}