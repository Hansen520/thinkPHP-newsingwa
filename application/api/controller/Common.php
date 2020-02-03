<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 22:03
 */

namespace app\api\controller;
use think\Cache;
use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use app\common\lib\IAuth;
use app\common\lib\Time;


/**
 * Class Common
 * @package app\api\controller
 */
class Common extends Controller
{
    /**
     * 定义header信息
     * @var string
     */
    public $headers = '';

    public $page =1;
    public $size = 10;
    public $form = 0;
    /*
     * 初始化方法
     * */
    public  function _initialize()
    {
        $this->checkRequestAuth();
        //$this->testAes();
    }
/*
 * 检查每次app请求的数据是否合法
 * */
    public function checkRequestAuth(){
        //首先需要获取postman创建的数据
        $headers = request()->header();
        //halt($headers);
        //todo
        //sign 加密需要 客户端工程师， 解密 ： 服务端工程师

        //1 headers body 仿照sign，做参数的加解密
        //2
        //3
        //基础参数校验
/*        if(empty($header['sign'])){
            throw new ApiException('sign不存在',400);
        }*/
        if(!in_array($headers['app_type'],config('app.apptypes'))){
            throw new ApiException('apptypes不合法',400);
        }

        //需要sign
/*        if(!IAuth::checkSignPass($headers)){
            throw new ApiException('授权码sign失败',401);
        }*/

        Cache::set($headers['sign'],1,config('app.app_sign_cache_time'));
        /*
         * 为了保证安全性的唯一性，我们可以采用1.文件。2.mysql。3.redis的
         * 方式去做，文件可以只是用一台机器，而mysql和redis可以采用分布式服务器的做。
         * */
        $this->headers = $headers;
    }


    public function testAes()
    {
        $data = [
            'did' => '12345dg',
            'vaersion' => 1,
            'time' => Time::get13TimeStamp()
        ];
        //halt($data);
        $str = 'JdrAqVhpP9SX4AM/c4QnwLqe5YPbBfuhlYBcB6GCr4g=';
        //col9j6cqegAKiiey3IrXWj1m36GMTpeeCAQyejFJ9ZgcmOrqITM8baRKnEXXQKGy
        echo  IAuth::setSign($data);exit;
        echo (new Aes()) -> decrypt($str);exit;
    }

    /**
     * 获取处理新闻的内容数据
     * @param array $news
     * @return array
     */
    protected function getDealNews($news = []){
        if(empty($news)){
            return [];
        }
        $cats = config('cat.lists');
        foreach ($news as $key => $new){
            $news[$key]['catname'] = $cats[$new['catid']] ? $cats[$new['catid']] : '-';
        }
        return $news;
    }

    /*
 * 获取分页page size内容
 * */
    public  function  getPageAndSize($data){
        $this->page =!empty($data['page']) ? $data['page'] : 1;
        $this->size =!empty($data['size']) ? $data['size']:config('paginate.list_rows');
        $this->form = ($this->page -1) * $this->size;
    }
}