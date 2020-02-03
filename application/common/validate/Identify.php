<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/12/26
 * Time: 12:57
 */
namespace app\common\validate;

use think\Validate;
class  Identify extends Validate{
    protected  $rule=[
        'id' => 'require|number|length:11',

    ];
}