<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;

class BrandController extends BackedContoller
{
    public function behaviors(){
        return [
            'accessFilter'=>[
                'class'=>AccessFilter::className(),
                'only'=>['index','add','edit','del']
            ]
        ];
    }
    //显示品牌列表
    public function actionIndex()
    {
        $query=Brand::find();
        $page=new Pagination(
            [
                'totalCount'=>$query->count(),
                'defaultPageSize'=>5,
            ]
        );
        $brands=$query->offset($page->offset)->limit($page->limit)->where('status >=0')->all();
        return $this->render('index',['brands'=>$brands,'page'=>$page]);
    }
    //添加品牌
    public function actionAdd(){
        //创建表单模型
        $model=new Brand();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
//            $model->imgName=UploadedFile::getInstance($model,'imgName');
//            var_dump();exit;
            //验证
            if($model->validate()){
                //判断是否上传图片
//                if($model->imgName){
//                    //处理图片
//                    $imgPath='/images/brand/'.uniqid().'.'.$model->imgName->extension;
//                    $model->imgName->saveAs(\Yii::getAlias('@webroot').$imgPath,false);
//                    $model->logo=$imgPath;
//                }

                //保存数据
                $model->save(false);
                \Yii::$app->session->setFlash('success','品牌添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());

            }
        }

        //调用视图,传递表单模型
        return $this->render('add',['model'=>$model]);
    }
    //修改品牌
    public function actionEdit($id){
        //创建表单模型
        $model=Brand::findOne($id);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
//            $model->imgName=UploadedFile::getInstance($model,'imgName');
            //验证
            if($model->validate()){
                //判断是否上传图片
//                if($model->imgName){
//                    //处理图片
//                    $imgPath='/images/brand/'.uniqid().'.'.$model->imgName->extension;
//                    $model->imgName->saveAs(\Yii::getAlias('@webroot').$imgPath,false);
//                    $model->logo=$imgPath;
//                }
                //保存数据
                $model->save();
                \Yii::$app->session->setFlash('success','品牌修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());

            }
        }

        //调用视图,传递表单模型
        return $this->render('add',['model'=>$model]);
    }
    //删除品牌---逻辑删除(status改为-1)
    public function actionDel($id){
        $brand=Brand::findOne($id);
        $brand->status=-1;
        if($brand->save()){
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['brand/index']);
        }

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
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
//                    $action->output['fileUrl'] = $action->getWebUrl();
                    $imgPath=$action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    $ak = 'IfWM8yUPdJf94XI9yLcRQ-XG6fzBM1_5_pKJPxRh';
                    $sk = 'shXe5U-z9KYlSLGpJMEnbKv3vTqOvfEywNPEzv_l';
                    $domain = 'http://or9uffwr7.bkt.clouddn.com/';
                    $bucket = 'yii2shop';

                    $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
                    $filename=\Yii::getAlias('@webroot').$imgPath;
//                    $filename=$action->getSavePath();

                    $qiniu->uploadFile($filename,$imgPath);
                    $url = $qiniu->getLink($imgPath);
                    $action->output['fileUrl'] = $url;
//                    var_dump($url);exit;
                },
            ],
        ];
    }
    //七牛云插件
    public function actionQiniu(){
        $ak = 'IfWM8yUPdJf94XI9yLcRQ-XG6fzBM1_5_pKJPxRh';
        $sk = 'shXe5U-z9KYlSLGpJMEnbKv3vTqOvfEywNPEzv_l';
        $domain = 'http://or9uffwr7.bkt.clouddn.com/';
        $bucket = 'yii2shop';

        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        $filename=\Yii::getAlias('@webroot').'/upload/test.gif';
        $key = 'test.gif';
        $qiniu->uploadFile($filename,$key);
        $url = $qiniu->getLink($key);
        var_dump($url);
    }
}
