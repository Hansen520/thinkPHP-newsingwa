<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 16:09
 */

namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\IAuth;
use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\model\news;
use app\common\model\user;



class Upvote extends AuthBase {

    /**
     * 新闻点赞功能开发
     * @return array
     */
    public function save(){
        $id = input('post.id',0,'intval');
        halt($id);

            if(empty($id)){
                return show(config('code.error'),'id不存在',[],403);
            }

            $data = [
                'user_id' => $this->user->id,
                'news_id' => $id
            ];

            //查询库里是否存在 点赞
        $userNews = model('UserNews')->get($data);

        if($userNews){
            return show(config('code.error'),'已经被点赞过了','',401);
        }

        try{
            $userNewsId = model('UserNews')->add($data);
            if($userNewsId){
                model('News')->where(['id'=>$id])->setInc('upvote_count');//阅读数的自增
                return show(config('code.success'),'OK',[],202);
            }else{
                return show(config('code.error'),'内部错误点赞失败','',500);
            }
        }catch (\Exception $e){
            return show(config('code.error'),'内部错误点赞失败','',403);
        }
    }

    /**
     * 取消点赞
     */
    public function delete(){
        $id = input('delete.id',0,'intval');

        if(empty($id)){
            return show(config('code.error'),'id不存在',[],403);
        }

        $data = [
            'user_id' => $this->user->id,
            'news_id' => $id
        ];
        $userNews = model('UserNews')->get($data);

        if(empty($userNews)){
            return show(config('code.error'),'没有被点赞，无法取消','',401);
        }
        try{
            $userNewsId = model('UserNews')
                ->where($data)
                ->delete();
            if($userNewsId){
                model('News')->where(['id'=>$id])->setDec('upvote_count');//阅读数的自减
                return show(config('code.success'),'OK',[],202);
            }else{
                return show(config('code.error'),'取消失败',[],500);
            }
        }catch (\Exception $e){
            return show(config('code.error'),'内部错误点赞失败',[],500);
        }
    }

    /**
     * 查看文章是否被该用户点赞
     */
    public function read(){
        $id = input('param.id',0,'intval');
        if(empty($id)){
            return show(config('code.error'),'id不存在',[],403);
        }
        $data = [
            'user_id' => $this->user->id,
            'news_id' => $id
        ];
        $userNews = model('UserNews')->get($data);

        if($userNews){
            return show(config('code.success'),'OK',['isUpvote'=>1],200);
        }else{
            return show(config('code.success'),'OK',['isUpvote'=>0],200);
        }

    }



}