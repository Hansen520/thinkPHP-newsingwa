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


class News extends Common {
    public function index(){
        //自己用validate验证机制
        $data = input('get.');
       $whereData['status'] = config('code.status_normal');
        if (!empty($data['catid'])){
            $whereData['catid'] = input('get.catid',0,'intval');
        }
       if (!empty($data['title'])){
           $whereData['title'] = ['like','%'.$data['title'].'%'];
       }

       try{
           $this->getPageAndSize($data);
           $total = model('News')->getNewsCountByCondition($whereData);
           $news = model('News')->getNewsByCondition($whereData);
        }catch (\Exception $e){
           return $this->result('',0,'传递失败');
       }

       $result = [
           'total' => $total,
           'page_num' => ceil($total/$this->size),
           'list' => $this->getDealNews($news),
       ];
       return show(config('code.success'),'OK',$result,200);

    }

    /**
     * 获取详情接口
     */
    public function read(){
        //详情页面APP -》1、x.com/3.html 2、接口
        $id = input('param.id',0,'intval');
        if(empty($id)){
            new ApiException('id is not',404);
        }
        //通过id去获得数据表里面的数据
        try{
            $news = model('News')->get($id);
            if(empty($news) || $news->status != config('code.status_normal')){
                return new ApiException('不存在这条新闻',404);
            }
        }catch (\Exception $e){
            return new ApiException('error',400);
        }
        try{
            model('News')->where(['id'=>$id])->setInc('read_count');//阅读数的自增
        }catch (\Exception $e){
            return new ApiException('error',400);
        }

        $cats = config('cat.lists');
        $news->catname = $cats[$news->catid];
        return show(config('code.success'),'OK',$news,200);
    }
}