<?php
namespace  app\admin\controller;

use think\Controller;

class Index extends Base {
    public function index(){
        //halt(session(config('admin.session_user'),'',config('admin.session_user_scope')));
            return $this->fetch();
    }

    public function clock(){
        return '--------------------------Hello 我的名字叫做金汉生😀！-----------------------'.$this->fetch();
    }
}