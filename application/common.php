<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * @param $obj
 * @return string
 */
function pagination($obj){
    if (!$obj) {
        return '';
    }
    $params = request()->param();
    return '<div class="imooc-app">'.$obj->appends($params)->render().'</div>';
}

function getCatName($catId){
    if(!$catId){
        return '';
    }
    $cats = config('cat.lists');
    return !empty($cats[$catId]) ? $cats[$catId] : '';
}
function isYesNo($str){
    return $str ? '<span style="color:red">是<span>' : '<span></span>否<span>';
}

/*
 * 状态
 * */
function status($id,$status){
    $controller = request()->controller();
    $sta = $status == 1 ? 0 : 1;
    $url = url($controller.'/status',['id'=>$id,'status'=>$sta]);
    if($status ==1){
        $str = "<a href='javascript:;' title='修改状态' status_url='".$url."' onclick='app_status(this)'><span class='label label-success radius'>正常</span></a>";
    }else if($status ==0){
        $str = "<a href='javascript:;' title='修改状态' status_url='".$url."' onclick='app_status(this)'><span class='label label-danger radius'>待审</span></a>";
    }
    return $str;
}

/*
 * 通用化API接口数据的输出
 * int $status 业务状态码
 * [] $data 数据
 * int $httpCode http状态码
 * array
 * */
//写到这里之后就不用担心下面参数，直接利用方法调用即可
function show($status,$message,$data=[],$httpCode=200){

    $data = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
    return json($data,$httpCode);
}