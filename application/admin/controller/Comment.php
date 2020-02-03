<?php
namespace  app\admin\controller;

use think\Controller;

class Comment extends Base {
    public function index(){
        $data = input('param.');
        $query = http_build_query($data);
        $whereData = [];
        //halt($query);
        //获取数据 然后数据填充
        //转换查询条件
        if (!empty($data['start_time'])&&!empty($data['end_time'])&&$data['end_time']>$data['start_time']){
            $whereData['create_time'] = [
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])],
            ];
        }
        if(!empty($data['username'])){
            $whereData['username'] = ['like','%'.$data['username'].'%'];
        }

        $this->getPageAndSize($data);

        //获取表里面的数据
        //$news = model('News')->getNewsByCondition($whereData,$this->from,$this->size);

        //获取满足条件的数据总数=》 有多少页
        $total = model('News')->getNewsCountByCondition($whereData);
        //echo $total;exit;
        //结合总数+size=》有多少页
        $pageTotal = ceil($total/$this->size);//1.1->2

        $comment = model('Comment')->getComment();
        return $this->fetch('',[
            'comment' => $comment,
            'pageTotal' => $pageTotal,
            'start_time'=>empty($data['start_time']) ? '': $data['start_time'],
            'end_time'=>empty($data['end_time']) ? '': $data['end_time'],
            'username'=>empty($data['username']) ? '' : $data['username'],
            'query' => $query,
            'curr' => $this->page,
        ]);
    }
}