<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/12/26
 * Time: 16:44
 */

namespace app\common\model;
use think\Model;

class Version extends Base{
    /**
     * 通过apptype获取最后一条版本内容
     * @param string $appType
     */
    public function getLastNormalVersionByAppType($appType = ''){
        $data = [
            'status' => 1,
            'app_type' => $appType,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->order($order)
            ->limit(1)
            ->find();
    }
}