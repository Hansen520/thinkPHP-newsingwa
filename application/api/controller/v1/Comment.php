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
use app\common\model\User;
use app\common\model\News;
use app\common\model\UserNews;



class Comment extends AuthBase {
    /**
     *评论 - 回复功能的开发
     */
    public function save(){
        $data = input('post.',[]);
        //halt($data);
        //news_id content to_user_id parent_id
        //validate
        //news_id

        //$userNews = model('user_news')->where(['id'=>$data]);

        //$data['news_id'] = $param['news_id'];
        //halt($data['news_id']);

        $data['user_id'] = $this->user->id;
        try{
            $commentId = model('Comment')->add($data);
            if($commentId){
                //model('News')->where(['id'=>$id])->setInc('upvote_count');//阅读数的自增
                return show(config('code.success'),'OK',[],202);
            }else{
                return show(config('code.error'),'评论失败','',500);
            }
        }catch (\Exception $e){
            return show(config('code.error'),'评论失败','',500);
        }
    }

    /**
     * 评论列表
     */
    //v1.0
    /*public function read(){
        $newsId = input('param.id',0,'intval');
      //halt(input('param.id'));
        if(empty($newsId)){
            return new ApiException('id is not',403);
        }
        $param['news_id'] = $newsId;
        $count = model('Comment')->getNormalCommentsCountByCondition($param);
        //echo $count;
        $this->getPageAndSize(input('param.'));
        $comments = model('Comment')->getNormalCommnetsByCondition($param, $this->form, $this->size);

        $result = [
            'total' => $count,
            'page_num' => ceil($count / $this->size),
            'list' => $comments,
        ];

        return show(config('code.success'), 'OK', $result, 200);

    }*/
    //v2.0

    public function read(){
        // select * from ent_comment as a join ent_user as b on a.user_id = b.id and a.news_id=7;
        $newsId = input('param.id', 0, 'intval');
        if(empty($newsId)) {
            return new ApiException('id is not ', 404);
        }

        $param['news_id'] = $newsId;
        $count = model('Comment')->getCountByCondition($param);

        $this->getPageAndSize(input('param.'));
        $comments = model('Comment')->getListsByCondition($param, $this->form, $this->size);

        if($comments) {
            foreach($comments as $comment) {
                $userIds[] = $comment['user_id'];
                if($comment['to_user_id']) {
                    $userIds[] = $comment['to_user_id'];
                }
            }
            $userIds = array_unique($userIds);
        }

        $userIds = model('User')->getUsersUserId($userIds);
        //halt($userIds);
        if(empty($userIds)) {
            $userIdNames = [];
        }else {
            foreach($userIds as $userId) {
                $userIdNames[$userId->id] = $userId;
            }
        }

        $resultDatas = [];
        foreach($comments as $comment)  {
            $resultDatas[] = [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'to_user_id' => $comment->to_user_id,
                'content' => $comment->content,
                'username' => !empty($userIdNames[$comment->user_id]) ? $userIdNames[$comment->user_id]->username : '',
                'tousername' => !empty($userIdNames[$comment->to_user_id]) ? $userIdNames[$comment->to_user_id]->username : '',
                'parent_id' => $comment->parent_id,
                'create_time' => $comment->create_time,
                'image' => !empty($userIdNames[$comment->user_id]) ? $userIdNames[$comment->user_id]->image : '',
            ];
        }

        $result = [
            'total' => $count,
            'page_num' => ceil($count / $this->size),
            'list' => $resultDatas,
        ];

        return show(config('code.success'), 'OK', $result, 200);
    }

}