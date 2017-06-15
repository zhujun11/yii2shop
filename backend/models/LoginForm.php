<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/14
 * Time: 14:11
 */
namespace backend\models;
use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $code;
    public $autoLogin;
//    public $id;
//    static public $checkautoLogin=[0=>'否',1=>'是'];
    public function rules(){
        return [
            [['username','password'],'required'],
            ['password','valitepwd'],
            ['autoLogin','integer'],
//            ['id','integer'],
            ['code','captcha','captchaAction'=>'user/captcha']
        ];
    }
    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'code'=>'验证码',
            'autoLogin'=>'是否自动登录',
        ];
    }
    public function valitepwd(){
        $user=User::findOne(['username'=>$this->username]);
        if($user){
             if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){

                 $user->last_time=time();
                 $user->last_ip=\Yii::$app->request->userIP;
                 $user->save();
                 //判断是否启用自动登录---并且使用一个变量保存login的第二个参数,过期时间
                 $duration=$this->autoLogin ? 24*3600 : 0;

                 \Yii::$app->user->login($user,$duration);

                 \Yii::$app->session->setFlash('success','登录成功');
                 return \Yii::$app->user->getIdentity();
             }else{

                 $this->addError('username','用户名或密码错误');
             }
        }else{
            $this->addError('username','用户名或密码错误');
        }
    }
}