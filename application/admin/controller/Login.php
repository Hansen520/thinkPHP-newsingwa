<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/2
 * Time: 16:26*/

namespace app\admin\controller;
use think\Controller;
use app\common\lib\IAuth;


class Login extends Base {
    //调用Base，判断用户是否登入，没有登入自动到登入页，下面一个方法是重写
    public  function _initialize(){
    }
    public function index(){
        $isLogin = $this->isLogin();
        if($isLogin){
            return $this->redirect('index/index');
        }else{
            //加载Login模块
            return $this->fetch();
        }
    }


    /*
     * 判断是否为post响应
     * 登入的相关业务
     * */
    public function check(){
        if(request()->isPost()){
            $data = input('post.');
            if (!captcha_check($data['code'])){
                $this->error('验证码不正确，请重新输入！');
            }
            //判断username password 方法和validate机制一样的
            //validate 判断user和password是否为空
            $validate = validate("AdminUser");
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            try{
                //对用户进行校验
                $user = model('AdminUser')->get(['username'=> $data['username']]);
            }catch (\Exceptionc $e){
                $this->success($e->getMessage());
            }

            if(!$user || $user->status != config('code.status_normal')){//config后面防止以后该的太痛苦而设立
                $this->error("该用户不存在");
            }
            //对密码进行，校验，这里涉及高度复用
            if(IAuth::setPassword($data['password']) != $user['password']){
                $this->error('密码不正确');
                //halt($user);
            }
            //1.更新数据库 登入时间 登入ip
            //2session
            try{
                session(config('admin.session_user'),$user,config('admin.session_user_scope'));
            }catch (\Exception $e){
                $this->success($e->getMessage());
            }
//上面有个错误就是说，用try catch时，会出现用户登入不正确，因为error已经用到了异常处理机制，所以，我们要分开用try catch去处理问题
            $this->success('登入成功','index/index');
        }else{
            $this->error('请求不合法');
        }

    }

    public function  welcome(){
        return "HELLO 我的名字叫做金汉生！";
    }
    /*
     * 退出登入的逻辑
     * 1.清空session
     * 2.跳转到登入页面     *
     * */

    public  function logout(){
        //退出登入只要删除他的作用域即可
        session(null,config('admin.session_user_scope'));
        //跳转
        $this->redirect('login/index');//redirect也是tp5方法
    }

}