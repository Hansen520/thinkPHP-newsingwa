<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/12/26
 * Time: 16:44
 */

namespace app\common\model;
use think\Model;

class News extends Base{
    /*
     * 后台自动化分页
     * */
    public function getNews($data = [])
    {
        $data['status'] =[
            'neq',config('code.status_delete')
        ];

        $order = ['id' => 'desc'];//排序
        //查询

        $result = $this -> where($data)
            ->order($order)//排序机制
            ->paginate();//分页机制
        return $result;
    }

    /*
     * 根据条件获取列表的数据
     * */
    public function getNewsByCondition($condition = [],$form = 0,$size=5){
        if(!isset($condition['status'])){
            $condition['status'] = [
                'neq' , config('code.status_delete')
            ];
        }

        $condition['status'] =[
            'neq',config('code.status_delete')
        ];

        $order = ['id' => 'desc'];//排序

        //limit a,b限制每页的大小，从哪一页开始

        $result = $this -> where($condition)
            ->field($this->_getListField())
            ->limit($form,$size)
            ->order($order)
            ->select();
        //echo $this->getLastSql();
        return $result;
    }

    /*
     * 根据条件来获取列表的数据总数
     * */
    public function getNewsCountByCondition($condition = []){
        if(!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }
         return $this->where($condition)
            ->count();
        //echo $this->getLastSql();
    }

    /**
     * 获取首页头图数据
     * @param int $num
     * @return array
     */
    public function getIndexHeadNormalNews($num = 4){
        $data = [
          'status' => 1,
          'is_head_figure' => 1,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->field($this->_getListField())
            ->order($order)
            ->limit($num)
            ->select();
    }

    /**
     * 获得推荐的数据
     */
    public function getPartitionNormalNews($num = 20){
        $data = [
            'status' => 1,
            'is_position' => 1,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->field($this->_getListField())
            ->order($order)
            ->limit($num)
            ->select();
    }

    /**
     * 获取排行榜数据
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRankNormalNews($num = 5){
        $data = [
            'status' => 1,
        ];
        $order = [
            'read_count' => 'desc',
        ];
        return $this->where($data)
            ->field($this->_getListField())
            ->order($order)
            ->limit($num)
            ->select();
    }
    /**
     * 通用化获取参数的数据字段
     */
    private function  _getListField(){
        return [
            'id',
            'catid',
            'title',
            'read_count',
            'image',
            'status',
            'is_position',
            'update_time',
            'create_time'
        ];
    }
}