<?php
/**
 * Created by PhpStorm.
 * User: HS
 * Date: 2017/10/2
 * Time: 21:11
 */

namespace app\common\lib;
use app\common\lib\Aes;
use think\Cache;
use ali\api_sdk\lib\Api\Sms\Request\V20170525\SendSmsRequest;
use ali\api_sdk\lib\Core\getAcsResponse;
use ali\api_sdk\lib\Core\Config;
use ali\api_sdk\lib\Core\Profile\DefaultProfile;
use ali\api_sdk\lib\Core\DefaultAcsClient;
use think\Log;

// 加载区域结点配置
Config::load();

/**
 * 阿里大于发送短信基础类库
 * Class Alidayu
 * @package app\common\lib
 */
class Alidayu
{
    const LOG_TPL = "alidayu";
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
        $accessKeyId = config('aliyun.accessKeyId'); // AccessKeyId

        $accessKeySecret = config('aliyun.accessKeySecret'); // AccessKeySecret

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
     * 静态变量保存全局的实例
     * @var null
     */
    private static $_instance = null;

    /**
     * 私有的构造方法
     * Alidayu constructor.
     */
    private function __construct()
    {

        }

    /**
     * 静态方法 单例模式统一入口
     */
    public static function getInstance()
    {
        if(is_null(self::$_instance)){
            self::$_instance = new self();//实例化
        }

        return self::$_instance;
    }

    public function setSmsIdentify($phone = 0){
        $code = rand(1000,9999);
        try{
            // 初始化SendSmsRequest实例用于设置发送短信的参数
            $request = new SendSmsRequest();

            // 必填，设置短信接收号码
            $request->setPhoneNumbers($phone);

            // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
            $request->setSignName("我叫小Hansne");


            // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
            $request->setTemplateCode("SMS_126970559");

            // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
            $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
                "code"=>$code,
                "product"=>"dsd"
            ), JSON_UNESCAPED_UNICODE));

            // 可选，设置流水号
            $request->setOutId("yourOutId");

            // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
            $request->setSmsUpExtendCode("1234567");

            // 发起访问请求
            $acsResponse = static::getAcsClient()->getAcsResponse($request);
        }catch (\Exception $e){
            //记录日志
            Log::write(self::LOG_TPL."set------".$e->getMessage());
            return false;
        }

        //设置验证码失效时间
        Cache::set($phone,$code,config('aliyun.identify_time'));
        if($acsResponse->Code == true){
            //halt($acsResponse);
            return $acsResponse;
        }else{
            Log::write(self::LOG_TPL."set------111".json_encode($acsResponse));
            return false;
        }


    }
    /*
     * 根据手机号码查询验证码是否正常
     * */
    public function checkSmsIdentify($phone = 0){
        if(!$phone){
            return false;
        }
        return Cache::get($phone);
    }
    //send find
}