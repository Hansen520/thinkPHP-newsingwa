<?php
namespace  app\admin\controller;

use think\Controller;
use think\Request;

/*
 * 后台图片上传相关逻辑
 * */
class Image extends Base {
    public function upload(){
        $file = Request::instance()->file('file');
        //把图片上传到指定的文件夹中，这个左边静态文件public中会自动多出一个文件夹
        $info = $file -> move('upload');
        if($info && $info->getPathname()){
            $data = [
              'status' => 1,
              'message' => 'OK',
              'data' => '/'.$info->getPathname(),
            ];
            echo  json_encode($data);exit;//打印json数据
        }
        echo json_encode(['status'=>0,'message'=>'上传失败']);
    }

}