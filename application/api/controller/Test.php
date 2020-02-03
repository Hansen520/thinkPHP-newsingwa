<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/12
 * Time: 16:09
 */

namespace app\api\controller;
use app\common\lib\Alidayu;
use app\common\lib\IAuth;
use think\Controller;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use ali\api_sdk\lib\Api\Sms\Request\V20170525\SendSmsRequest;
use ali\api_sdk\lib\Core\getAcsResponse;
use ali\api_sdk\lib\Core\Config;
use ali\api_sdk\lib\Core\Profile\DefaultProfile;
use ali\api_sdk\lib\Core\DefaultAcsClient;
use app\common\lib\aliyun;


// 加载区域结点配置
Config::load();
class Test extends Common
{
    public function index()
    {
        return [
            'sgsg',
            'sgsas',
        ];
    }

    public function update($id = 0)
    {
        //$id = input('put.id');
        halt(input('put.id'));
        //return $id;
    }

    /*
     * post 新增
     * */
    public function save()
    {
        $data = input('post.');
        //获取到提交的数据，插入库
        //给客户端APP=》接口数据
        return show('1', 'OK', (new Aes())->encrypt(json_encode(input('post.'))), 201);
        //中间，我们也可以用(new Aes())->encrypt(json_encode(input('post.')))，对input('post.')进行加密处理

    }



    static $acsClient = null;

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = "LTAILlGuHVbc0JEs"; // AccessKeyId

        $accessKeySecret = "4czbGtxjmwuB4YGcw0PZ7o93w63ibt"; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送短信
     * @return stdClass
     */
    public static function sendSms() {

    /*    // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers("18958769872");

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName("我叫小Hansne");

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode("SMS_126970559");

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
            "code"=>"753698",
            "product"=>"dsd"
        ), JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
        $request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);
        //halt($acsResponse);
        //return $acsResponse;*/
    }
    public function testsend(){
        Alidayu::getInstance()->setSmsIdentify('18958769872');
    }

/*
 * app登入和web登入
 * web 登入使phpsessionid ，app->token(唯一性)，
 * 在登入状态下，所有请求必须带token
 * token也需要失效时间
 * */


public function token(){
    echo IAuth::setAppLoginToken();
}
}