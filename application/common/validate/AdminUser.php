<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/12/26
 * Time: 12:57
 */
namespace app\common\validate;

use think\Validate;
class  AdminUser extends Validate{
    protected  $rule=[
        'username' => 'require|max:20',
        'password' => 'require|max:20',
    ];
}