<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/2
 * Time: 21:15

 里面主要是放一下功能性的文件*/

return [
    'password_pre_halt' => '_#sing_ty',//密码加密盐
    'aeskey' => 'sgg45747ss223455',//aes 密钥 ， 服务端和客户端必须保持一致
    'apptypes' => [
      'ios',
      'android',
    ],
    'app_sign_time' => 10,//sign失效时间
    'app_sign_cache_time' => 20,//sign缓存失效时间
    'login_time_out_day'=>1,//登入token的失效时间
];