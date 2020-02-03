<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 16:09
 */

namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Alidayu;
use app\common\lib\IAuth;
use app\common\model\User;
use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;


class Login extends Common
{
    public function save()
    {
     if (!request()->isPost()){
         return show(config('code.error'),'你那儿没有权限哦','',403);
     }
     $param = input('param.');
     if(empty($param['phone'])){
         return show(config('code.error'),'手机号码不合法','',404);
     }
     if (empty($param['code']) && empty($param['password'])){
         return show(config('code.error'),'手机短信验证不合格','',404);
     }

    if(!empty($param['code'])){
     //validate严格校验
        $code = Alidayu::getInstance()->checkSmsIdentify($param['phone']);
        //判断手机验证码和获得的验证码是否匹配
     if($code != $param['code']){
         return show(config('code.error'),'手机验证码不存在','',404);
     }
    }

       $token =  IAuth::setAppLoginToken($param['phone']);
        $data = [
            'token' => $token,
            'time_out'=>strtotime("+".config('app.login_time_out_day')),
        ];
        //halt($data['time_out']);
     //查询手机号是否存在
       $user = User::get(['phone'=>$param['phone']]);
        if($user && $user->status == 1){
            if (!empty($param['password'])){
                //判定用户的密码 和$PARAM['PASSWORD'] 加密之后
                if(IAuth::setPassword($param['password']) != $user->password){
                    return show(config('code.error'),'密码不正确',[],4.6);
                }
            }
            //更新的逻辑
            //halt($data);
            $id = model('User')->save($data,['phone'=>$param['phone']]);
        }else{
            if(!empty($param['code'])){
        //第一次登入 注册数据
        $data['username']= 'Hansen-'.$param['phone'];
        $data['status']=1;
        $data['phone']=$param['phone'];
        $id = model('User')->add($data);
            }else{
                return show(config('code.error'),'用户不存在',[],4.6);
            }
        }

     $obj = new Aes();
     if($id){
         $result = [
             //这个token使'token'QERLV/0Zg1u5y9//w0rqummTVYWSaaX53DHX2HNrHNc0PI6+Ib849q3vYaS67R7I
             'token' => $obj->encrypt($token."||".$id),//encrypt使aes加密算法里面的,括号为加密方式
         ];
         return show(config('code.success'),'OK',$result);
     }else{
         return show(config('code.error'),'登入失败',[],403);
     }
    }

    /**
     * 退出登入
     */
/*    public function logout(){
        token(null,'token');
    }*/
}