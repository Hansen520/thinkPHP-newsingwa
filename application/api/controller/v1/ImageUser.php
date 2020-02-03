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
use think\Request;



class ImageUser extends AuthBase
{
    public function save(){
        //print_r($_FILES);
        $file = Request::instance()->file('file');
        //把图片上传到指定的文件夹中，这个左边静态文件public中会自动多出一个文件夹
        $info = $file -> move('uploadUser');
        if($info && $info->getPathname()){
            $data = [
                'status' => 1,
                'message' => 'OK',
                'data' => '/'.$info->getPathname(),
            ];
            //return show(config('code.success'),'OK',$data);
            echo  json_encode($data);exit;//打印json数据
        }
        echo json_encode(['status'=>0,'message'=>'上传失败']);
    }
}