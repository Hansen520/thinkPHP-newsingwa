<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 16:09
 */

namespace app\api\controller\v1;

use app\api\controller\Common;
use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\lib\Alidayu;


class Identify extends Common {
    /**
     * post
     * 设置短信验证码
     */
   public function save(){
        if(!request()->isPost()){
            return show(config('code.error'),'您提交的数据不合法',[],403);
        }
        //校验数据
       $validate = validate('Identify');
        if(!$validate->check(input('post.'))){
            return show(config('code.error'),$validate->getError(),[],403);
        }
        $id = input('param.id');//id为手机号
        if(Alidayu::getInstance()->setSmsIdentify($id)){//id为手机号码
            return show(config('code.success'),'OK');
        }else{
            return show(config('code.error'),'error',[],403);
        }

   }
}