<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\User;
use yii\data\Pagination;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    //管理员列表
    public function actionIndex()
    {
        $query=User::find();
        $page=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>10,
        ]);
        $users=$query->offset($page->offset)->offset($page->offset)->where(['<>','id',\Yii::$app->user->id])->all();

        return $this->render('index',['users'=>$users,'page'=>$page]);
    }
    //添加管理员
    public function actionAdd(){
        $model=new User();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());

            if($model->validate()){

//                var_dump($model);exit;
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['user/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    //修改管理员
    public function actionEdit($id){
        $model=User::findOne($id);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());

            if($model->validate()){
                $model->created_at=time();
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
//                var_dump($model);exit;
                $model->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['user/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    //禁用
    public function actionDisable($id)
    {
        $model = User::findOne($id);
        $model->status=0;//逻辑删除
        $model->save();
        //$model->delete();//彻底删除
        \Yii::$app->session->setFlash('success','禁用成功');
        return $this->redirect(['user/index']);
    }
    //启用
    public function actionEnable($id)
    {
        $model = User::findOne($id);
        $model->status=1;
        $model->save();
        //$model->delete();//彻底删除
        \Yii::$app->session->setFlash('success','启用成功');
        return $this->redirect(['user/index']);
    }
    //彻底删除
    public function actionDel($id)
    {
        $model = User::findOne($id);
        $model->delete();//彻底删除
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['user/index']);
    }

    //登录页面
    public function actionLogin(){
        $model=new LoginForm();


            $request=new Request();
            if($request->isPost){
                $model->load($request->post());
                if($model->validate()){
                    \Yii::$app->session->setFlash('success','登录成功');
                    $this->redirect(['user/index']);
                }else{
                    var_dump($model->getErrors());
                }
            }
            return $this->render('login',['model'=>$model]);

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


    public function actionLogout(){
        \Yii::$app->user->logout();
        $cookies=\Yii::$app->response->cookies;
        $cookies->remove('id');
        return $this->redirect(['user/login']);
    }
}
