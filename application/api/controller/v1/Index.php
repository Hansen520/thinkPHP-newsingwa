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
use think\Log;


class Index extends Common {
    /**
     *获取首页接口
     * 1.头图 4-6
     * 2.推荐位列表 默认40条
     * @return \think\response\Json
     */
    public function index(){
       $heads = model('News')->getIndexHeadNormalNews();
       $heads =$this->getDealNews($heads);

       $positions = model('News')->getPartitionNormalNews();
       $positions =$this->getDealNews($positions);

       $result = [
           'heads' => $heads,
           'position' => $positions,
       ];

        return show(config('code.success'),'OK',$result,200);
    }
    /*
     *客户端初始化接口
     * 1、检测APP是否需要升级
     * */
    public function init(){
        //app_type 去ent_version 查询
        $version = model('Version')->getLastNormalVersionByAppType($this->headers['app_type']);

        if(empty($version)){
            return new ApiException('error',404);
        }
        if ($version->version > $this->headers['version']){
            $version->is_update = $version->is_force == 1 ? 2 : 1;//2强制更新，1不用强制
        }else{
            $version->is_update = 0;//0不更新，1需要更新
        }

        //记录用户的基本信息 ，用于统计
        $actives = [
            'version'=>$this->headers['version'],
            'app_type'=>$this->headers['app_type'],
            'did'=>$this->headers['did'],
        ];
        try{
            model('AppActive')->add($actives);//继承model 里base类的add方法，给active数据库添加数据
        }catch (\Exception $e){
            //todo
            Log::write();
        }

        return show(config('code.success'),'OK',$version,200);
    }




}