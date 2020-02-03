<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/2
 * Time: 21:11
 */

namespace app\common\lib\Page;
use app\common\lib\Aes;
use think\Cache;


class Page
{
    /*
 * page
 * */
    public $page = '';
    /*
     * 每页显示多少条
     * */
    public $size ='';
    /*
     * 查询条件的起始值
     * */
    public $from = 0;

    /*
     * 获取分页page size内容
     * */
    public  function  getPageAndSize($data){
        $this->page =!empty($data['page']) ? $data['page'] : 1;
        $this->size =!empty($data['size']) ? $data[size]:config('paginate.list_rows');
        $this->from = ($this->page -1) * $this->size;
    }
}