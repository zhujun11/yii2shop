<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/14
 * Time: 14:11
 */
namespace backend\models;
use yii\base\Model;
use yii\web\Cookie;

class LoginForm extends Model{
    public $username;
    public $password;
    public $code;
    public $autoLogin;
    public $id;
//    static public $checkautoLogin=[0=>'否',1=>'是'];
    public function rules(){
        return [
            [['username','password'],'required'],
            ['password','valitepwd'],
            ['autoLogin','integer'],
            ['id','integer'],
            ['code','captcha','captchaAction'=>'login/captcha']
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
                 \Yii::$app->user->login($user);
                 if($this->autoLogin==1){
                     $cookies=\Yii::$app->response->cookies;
                     $cookie=new Cookie([
                         'name'=>'id',
                         'value'=>$user->id,
                         'expire'=>time()+3600*24*7,
                     ]);
                     $cookies->add($cookie);
                     $cookie=new Cookie([
                         'name'=>'pwd',
                         'value'=>$this->password,
                         'expire'=>time()+3600*24*7,
                     ]);
                     $cookies->add($cookie);
                 }
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