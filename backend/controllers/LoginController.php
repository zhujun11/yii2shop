<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/14
 * Time: 14:30
 */
namespace backend\controllers;
use backend\models\LoginForm;
use backend\models\User;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;

class LoginController extends Controller{
    //登录页面
    public function actionLogin(){
        $model=new LoginForm();
        $cookies=\Yii::$app->request->cookies;
        if($cookies->get('id') && $cookies->get('pwd')){
            $userid=$cookies->get('id')->value;
            $password=$cookies->get('pwd')->value;
            if($user=User::findOne($userid)){
                if(\Yii::$app->security->validatePassword($password,$user->password_hash)){
                    \Yii::$app->user->login($user);
                    $this->redirect(['login/user']);
                }
            }
//            var_dump($userid,$password);exit;
        }else{

            $request=new Request();
            if($request->isPost){
                $model->load($request->post());
                if($model->validate()){

                    $this->redirect(['login/user']);
                }else{
                    var_dump($model->getErrors());
                }
            }
            return $this->render('index',['model'=>$model]);
        }

//        var_dump($model);exit;


    }

    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }
    public function actionUser(){
        $user=\Yii::$app->user;
//        var_dump($user->isGuest);exit;
        if($user->id){
            return $this->redirect(['user/index']);
        }
    }

    public function actionLogout(){
        \Yii::$app->user->logout();
        $cookies=\Yii::$app->response->cookies;
        $cookies->remove('id');
        return $this->redirect(['login/login']);
    }
}