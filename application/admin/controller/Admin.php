<?php
namespace  app\admin\controller;

use think\Controller;
use app\common\lib\IAuth;

class Admin extends Controller{

        public function add(){
            //判定是否使post提交
            if(request() -> isPost()){
                //打印提交的数据
                //dump(input('post.')); halt() => dump(); exit
                $data =input('post.');
                //validate，判断密码和用户名是否为空，adminUser为
                $validate = validate("AdminUser");
                if(!$validate->check($data)){
                    $this->error($validate->getError());
                }

                $data['password'] = IAuth::setPassword($data['password']);//对密码进行md5加密，并且用sing_ty保证安全
                $data['status'] =1;//表示状态，如果是1表示正确

                /*
                 * 这边做两个测试
                 * 1 判断是否有exception 异常
                 * 2 add id是否已经加上
                 * */
                try{
                    $id = model('AdminUser')->add($data);
                }catch(\Exception $e){
                    $this->error($e->getMessage());
                }
                if($id){
                    $this->success('id='.$id.'的用户新增成功');
                }else{
                    $this->error('error');
                }

            }else{
                return $this->fetch();
            }

        }
}