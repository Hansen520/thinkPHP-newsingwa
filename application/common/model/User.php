<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/12/26
 * Time: 16:44
 */

namespace app\common\model;
use think\Model;

class User extends Base{

    /**
     * @param array $userIds
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUsersUserId($userIds = []) {
        $data = [
            'id' => ['in', implode(',', $userIds)],// in
            'status' => 1,
        ];

        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->field(['id', 'username', 'image'])
            ->order($order)
            ->select();
    }
}