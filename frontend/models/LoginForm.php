<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/19
 * Time: 14:01
 */
namespace frontend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class LoginForm extends Model{
    public $username;
    public $password;
    public $catpCode;
    public $remember;

    const SCENARIO_API_LOGIN='api_login';

    public function rules()
    {
        return [
            [['username','password'],'required'],
            //['catpCode','captcha'],
            ['catpCode','captcha','on'=>self::SCENARIO_API_LOGIN,'captchaAction'=>'api/captcha'],
            ['catpCode','string'],
            ['remember','integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'catpCode'=>'验证码',
            'remember'=>'自动登录',
        ];
    }
    public function loadData(){
        $member=Member::findOne(['username'=>$this->username]);
//        var_dump($member);exit;
        if ($member){
            $rs=\Yii::$app->security->validatePassword($this->password,$member->password_hash);
            if ($rs){
                $member->last_login_time=time();
                $member->last_login_ip=ip2long(\Yii::$app->request->userIP);
                $member->save(false);
                //判断是否需要自动登录
                $duration= $this->remember ? 24*3600 : 0;
                //保存登录
                \Yii::$app->user->login($member,$duration);
                //登录成功,更新购物车数据
                //先获取COOKIE中记录,对比数据表,有就使用cookie中的数量,没就添加
                $cookies=\Yii::$app->request->cookies;
                $goods_cart=unserialize($cookies->get('cart'));
                //获取数据表信息
                if($goods_cart){
                    foreach ($goods_cart as $goods_id=> $amount){
                        $goods_table=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member->id]);
                        if ($goods_table){
                            $goods_table->amount=$amount;
                        }else{
                            $goods_table=new Cart();
                            $goods_table->goods_id=$goods_id;
                            $goods_table->member_id=$member->id;
                            $goods_table->amount=$amount;
                        }
                        $goods_table->save();
                        //删除COOKIE
                    }
                    $cookies=\Yii::$app->response->cookies;
                    $cookies->remove('cart');
                }

                return true;
            }else{
                $this->addError('password','用户名或密码错误');
                return false;
            }
        }else{
            $this->addError('password','用户名或密码错误');
            return false;
        }
    }
}