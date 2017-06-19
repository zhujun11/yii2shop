<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/13
 * Time: 17:04
 */
namespace backend\controllers;
use backend\models\GoodsImg;
use yii\web\Controller;
use xj\uploadify\UploadAction;
use yii\web\Request;

class GoodsimgController extends Controller{
    public function actionIndex($id){
        $imgs=GoodsImg::find()->where(['goods_id'=>$id])->all();
        return $this->render('index',['imgs'=>$imgs,'goods_id'=>$id]);
    }

    public function actionAdd($goodsid){
        $model=new GoodsImg();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $data=$request->post();
            $urls=$data['url'];
            foreach ($urls as $url){
                $model->url=$url;
                $model->goods_id=$goodsid;
                $model->save();
                $model=new GoodsImg();
            }
            \Yii::$app->session->setFlash('success','上传成功');
            return $this->redirect(['goodsimg/index','id'=>$goodsid]);
        }

        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=GoodsImg::findOne($id);
        $goods_id=$model->goods_id;
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $data=$request->post();
            $urls=$data['url'];
            foreach ($urls as $url){
                $model->url=$url;
                $model->save();
                $model=new GoodsImg();
            }
            \Yii::$app->session->setFlash('success','上传成功');
            return $this->redirect(['goodsimg/index','id'=>$goods_id]);
        }

        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDel($id){
        $img=GoodsImg::findOne($id);
        $goods_id=$img->goods_id;
        $img->delete();
        \Yii::$app->session->setFlash('success','删除成功');

        return $this->redirect(['goodsimg/index','id'=>$goods_id]);
    }


    //uploadify插件
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
}