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


class Rank extends Common {
    /**
     * 获取排行榜数据列表
     * 1、获取数据库，然后read_count排序 5 - 10
     * 2、优化 redis
     */
    public function index(){
        try{
            $ranks = model('News')->getRankNormalNews();
            $ranks = $this->getDealNews($ranks);
        }catch (\Exception $e){
            return new ApiException('error',400);
        }
        return show(config('code.success'),'OK',$ranks,200);
    }
}