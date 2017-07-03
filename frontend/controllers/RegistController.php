<?php

namespace frontend\controllers;


use frontend\models\LoginForm;
use frontend\models\Member;

class RegistController extends \yii\web\Controller
{
    public $layout='regist';
    //用户注册
    public function actionRegist()
    {
        $model=new Member();
        $model->scenario=Member::SCENARIO_WEB_REGISTER;
        if($model->load(\Yii::$app->request->post())&& $model->validate()){
//            $model->create_at=time();
//            $model->status=1;
//            $model->last_login_time=time();
//            $model->last_login_ip=ip2long(\Yii::$app->request->userIP);
//            $model->auth_key=\Yii::$app->security->generateRandomString();
//            $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
            if ($model->save(false)){
                \Yii::$app->user->login($model);
                \Yii::$app->session->setFlash('success','注册成功');
                return $this->redirect(['home/index']);
            }

        }
        return $this->render('regist',['model'=>$model]);
    }
    //用户登录
    public function actionLogin(){
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->loadData()){
                \Yii::$app->session->setFlash('success','登录成功');

                return $this->redirect(['home/index']);
            }
        }

        return $this->render('login',['model'=>$model]);
    }
    //用户注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','欢迎再来');
        return $this->redirect(['home/index']);
    }
    //发送短信验证码
    public function actionSms(){
        //接收前台传过来的电话号码
        $tel=\Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '号码无效,请正确输入';
            exit;
        }
        //生成随机验证码
        $code=rand(100000,999999);
//        var_dump($code);exit;
        //调用短信发送组件
        $rs=\Yii::$app->sms->setNum($tel)->setParam(['code'=>$code])->send();
        if($rs){
            //使用缓存(redis),session,mysql保存手机号好随机码
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            echo 'success';
        }else{
            echo '发送失败';
        }
//        var_dump($tel);exit;
    }

}
