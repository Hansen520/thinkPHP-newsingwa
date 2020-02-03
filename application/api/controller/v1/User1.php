<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 16:09
 */

namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\IAuth;
use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\model\User;


class User1 extends AuthBase {

    /**
     * 获取用户信息
     * 用户的基本信息非常隐私、需要加密处理
     */
    public function read(){
        $obj = new Aes();
        return show(config('code.success'),'OK',$obj->encrypt($this->user));
    }

    /**
     * 修改数据
     */
    public function update(){
        $postData = input('param.');//这样子可以直接去数据库拿数据
        //validate 自己完成
        $data = [];
        if(!empty($postData['image'])){
            $data['image'] = $postData['image'];
        }
        if(!empty($postData['username'])){
            $data['username'] = $postData['username'];
        }
        if(!empty($postData['sex'])){
            $data['sex'] = $postData['sex'];
        }
        if(!empty($postData['signature'])){
            $data['signature'] = $postData['signature'];
        }
        if(!empty($postData['password'])){
            $data['password'] = IAuth::setPassword($postData['password']);
        }
        if (empty($data)){
            //panduan
            return show(config('code.error'),'数据不合法',[],403);
        }
        $username = User::get(['username'=>$postData['username']]);//获得库里数据
        if($username){
            return show(config('code.error'),'用户名存在请重新输入',[],403);
        }
        try{
           $id = model('User')->save($data,['id'=>$this->user->id]);
            if ($id){
                return show(config('code.success'),'OK',[],202);
            }else{
                return show(config('code.error'),'更新失败',[],401);
            }

        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),[],500);
        }



    }
}