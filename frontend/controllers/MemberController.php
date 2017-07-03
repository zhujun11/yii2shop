<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/19
 * Time: 19:55
 */
namespace frontend\controllers;

use chenkby\region\Region;
use frontend\models\Address;
use frontend\models\Locations;
use yii\helpers\Html;
use yii\web\Controller;


use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class MemberController extends Controller{
    public $layout='address';
    //显示和添加
    public function actionIndex(){
        $addresses=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
//        $addressModel=new Locations();
        $model=new Address();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->member_id=\Yii::$app->user->id;
            $default=Address::findOne(['status'=>1]);
            if ($default){
                $default->status=0;
                $default->save();
            }
            if ($model->save()){
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['member/index']);
            }

        }

        return $this->render('addAddress',['addresses'=>$addresses,'model'=>$model]);
    }
    //
    public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
            'class'=>\chenkby\region\RegionAction::className(),
//            'model'=>\app\models\Region::className()
            'model'=>Locations::className(),
        ];
        return $actions;
    }

    //
    //修改
    public function actionEdit($id){
        $model=Address::findOne($id);
        $addresses=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->member_id=\Yii::$app->user->id;
            $default=Address::findOne(['status'=>1]);
            if ($default){
                $default->status=0;
                $default->save();
            }
            if ($model->save()){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['member/index']);
            }

        }

        return $this->render('addAddress',['addresses'=>$addresses,'model'=>$model]);
    }
    //删除
    public function actionDel($id){
        $address=Address::findOne($id);
        $address->delete();
        return $this->redirect(['index']);
    }
    //设置默认地址
    public function actionDefault($id){
        $address=Address::findOne($id);
        $default=Address::findOne(['status'=>1]);
        if ($default){
            $default->status=0;
            $default->save();
        }
        $address->status=1;
        $address->save();
        return $this->redirect(['index']);
    }

    //测试阿里大于短信发送
//    public function actionSms(){
//        // 配置信息
//        $config = [
//            'app_key'    => '24480074',
//            'app_secret' => '43a9cf1b783dd283f73a1a05553a8d60',
//            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
//        ];
//        $code=rand(100000, 999999);
//
//        // 使用方法一
//        $client = new Client(new App($config));
//        $req    = new AlibabaAliqinFcSmsNumSend;
//
//        $req->setRecNum('13458541178')
//            ->setSmsParam([
//                'code' => $code
//            ])
//            ->setSmsFreeSignName('张军')
//            ->setSmsTemplateCode('SMS_71515165');
//
//        $resp = $client->execute($req);
//        var_dump($resp);
//        var_dump($code);
//    }



}