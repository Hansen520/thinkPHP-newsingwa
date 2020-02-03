<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/12/26
 * Time: 16:44
 */

namespace app\common\model;
use think\Model;
class Base extends Model{
    protected  $autoWriteTimestamp = true;//增加用户创建时间
    /**
     * 新增
     * @param $data
     * @return mixed
     *
     */
    public function add($data){
        //判断输入的是否为数组
        if(!is_array($data)){
            exception('传递数据不合法');
        }
        //allowfield方法是当data不在数组（mysql）里面就会报错
        $this->allowField(true)->save($data);

        return $this->id;
    }
}