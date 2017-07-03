<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/22
 * Time: 15:37
 */
namespace frontend\components;

use yii\base\Component;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class Sms extends Component{

    public $app_key;
    public $app_secret;
    public $sign_name;
    public $template_code;
    public $_num;
    public $_param;

    //设置手机号码
    public function setNum($num)
    {
        $this->_num=$num ;
        return $this;
    }
    //设置短信内容
    public function setParam(array $param){
        $this->_param=$param;
        return $this;
    }
    //设置短信签名
    public function setSign($sign){
        $this->sign_name=$sign;
        return $this;
    }
    //设置短信模板ID
    public function setTemplate($id){
        $this->template_code=$id;
        return $this;
    }


    public function send(){
        // 配置信息
        $config = [
            'app_key'    => $this->app_key,
            'app_secret' => $this->app_secret,
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];

        // 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $req->setRecNum($this->_num)
            ->setSmsParam($this->_param)
            ->setSmsFreeSignName($this->sign_name)
            ->setSmsTemplateCode($this->template_code);

        return $client->execute($req);


    }

}