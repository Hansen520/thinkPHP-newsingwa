<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2018/3/16
 * Time: 16:44
 */

namespace app\common\model;
use think\Model;
use think\Db;

class Comment extends Base{
    /**
     * 后台自动化分页
     * @param array $data
     * @return array
     */
    public function getComment($data = []){
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
    /**
     * 通过条件获取评论的数量
     * @param array $param
     */
    public function getNormalCommentsCountByCondition($param = []){
        //status = 1 自己加上去
       $count=  Db::table('ent_comment')
            ->alias(['ent_comment' => 'a','ent_user'=>'b'])
            ->join('ent_user','a.user_id = b.id AND a.news_id = '.$param['news_id'])
            ->count();
       return $count;
    }

    /**
     * 通过条件获得列表数据
     * @param array $param
     * @param int $form
     * @param int $size
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNormalCommnetsByCondition($param = [],$form = 0,$size = 5){
        $result = Db::table('ent_comment')
            ->alias(['ent_comment' => 'a', 'ent_user' => 'b'])
            ->join('ent_user', 'a.user_id = b.id AND a.news_id = ' . $param['news_id'])
            ->limit($form, $size)
            ->order(['a.id' => 'desc'])
            ->select();
        return $result;
    }

    /**
     *
     */
    public function getCountByCondition($param = []) {
        return $this->where($param)
            ->field('id')
            ->count();
    }

    /**
     * @param array $param
     * @param int $from
     * @param int $size
     */
    public function getListsByCondition($param = [], $from=0, $size = 5) {
        return $this->where($param)
            ->field('*')
            ->limit($from, $size)
            ->order(['id' => 'desc'])
            ->select();
    }

}