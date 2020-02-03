<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 16:09
 */

namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Alidayu;
use app\common\lib\IAuth;
use app\common\model\User;
use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;

/**
 * 客户端auth登入权限基础类库
 * 1、每个接口（需要登入，个人中心，点赞，评论）都需要去手机
 * 2、判定access_user_token是否合法
 * 3、用户信息->user
 *
 * Class AuthBase
 * @package app\api\controller\v1
 */
class AuthBase extends Common
{
    /**
     * 登入用户的基本信息
     * @var array
     */
    public $user = [];
    /**
     * 初始化
     */
    public function _initialize()
    {
        parent::_initialize();
        if(empty($this->isLogin())){
            throw new ApiException('您没有登入哦',401);
        }
    }

    /**
     * 判定是否登入
     */
    public function isLogin(){
        //halt($this->headers['access_user_token']);
        if(empty($this->headers['access_user_token'])){
            return false;
        }

        $obj = new Aes();//获得Aes对象
        $accessUserToken = $obj->decrypt($this->headers['access_user_token']);
        //echo $accessUserToken;exit;

        if(empty($accessUserToken)){
            return false;
        }
        if(!preg_match('/||/',$accessUserToken)){//如果没有匹配到||也是不成立的
            return false;
        }
        //对$accessUserToken以||进行切割，还原到原来的内容
        list($token,$id) = explode("||",$accessUserToken);
        $user = User::get(['token'=> $token]);

        //halt($user->status);

        if (!$user || $user->status != config("code.status_normal")){
            return false;
        }
        //halt(time());
        //echo 12;exit;
        //判断时间是否过期
/*        if(time() > $user->time_out){
            return false;
        }*/

        $this->user = $user;//直接把用户信息写入成员变量，到时候直接用就好了
        return true;
    }
}