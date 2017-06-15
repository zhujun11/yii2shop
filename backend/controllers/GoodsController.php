<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/12
 * Time: 11:28
 */
namespace backend\controllers;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\SerchForm;
use backend\models\SerchGoods;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use xj\uploadify\UploadAction;
use yii\web\Request;

class GoodsController extends Controller{
    //显示列表
    public function actionIndex(){
        $serchForm=new SerchForm();
        $query=Goods::find();//定义数据库数据操作方法
//        if ($keywords=\Yii::$app->request->get('keywords')){
//
//            $query->andWhere(['like','name',$keywords]);
//        }
        $serchForm->search($query);
        //获取分页对象
        $page=new Pagination([
            'totalCount'=>$query->count(),//总数据条数
            'defaultPageSize'=>10,//每页多少条
        ]);
        //获取每页所有数据
        $allGoods=$query->offset($page->offset)->limit($page->limit)->andWhere('status>0')->all();
        //调用视图显示
        return $this->render('index',['allGoods'=>$allGoods,'page'=>$page,'serchForm'=>$serchForm]);
    }
    //添加商品
    public function actionAdd(){
        //实例化Goods表和GoodsIntro表
        $model=new Goods();
        $introModel=new GoodsIntro();
        //实例化产品分类表
        $goodsCates=GoodsCategory::find()->asArray()->all();
        //获取品牌分类
        $brands=ArrayHelper::map(Brand::find()->asArray()->all(),'id','name');
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $introModel->load($request->post());

            //生成货号--规则:年月日+今天的第几个商品
            $strdate=date("Ymd",time());
            $daydate=date("Y-m-d",time());
            $dayCount=GoodsDayCount::findOne(['day'=>$daydate]);
            if(!$dayCount){
                //不存在就创建
                $dayCount=new GoodsDayCount();
                $dayCount->day=$daydate;
                $dayCount->count=0;

            }
            //拼接货号
            $sn=$strdate.str_repeat(0,(6-strlen($dayCount->count))).($dayCount->count+1);
            if($model->validate()){
                //保存goods表数据
                $model->sn=$sn;
                $model->create_time=time();
                $model->save();
                //保存goods_day_count表数据
                $dayCount->count+=1;
                $dayCount->save();
                //保存goods_intro表的数据
                $introModel->goods_id=$model->id;
                $introModel->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());
                var_dump($dayCount->getErrors());
            }

        }

        //调用视图显示
        return $this->render('add',
            [
                'model'=>$model,
                'introModel'=>$introModel,
                'brands'=>$brands,
                'goodsCates'=>$goodsCates,
            ]);
    }
    //商品修改
    public function actionEdit($id){
        //实例化Goods表和GoodsIntro表
        $model=Goods::findOne($id);
        $introModel=GoodsIntro::findOne(['goods_id'=>$id]);
        //实例化产品分类表
        $goodsCates=GoodsCategory::find()->asArray()->all();
        //获取品牌分类
        $brands=ArrayHelper::map(Brand::find()->asArray()->all(),'id','name');
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $introModel->load($request->post());
            if($model->validate()){
                //保存goods表数据
                $model->save();
                //保存goods_intro表的数据
                $introModel->goods_id=$model->id;
                $introModel->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());
            }

        }

        //调用视图显示
        return $this->render('add',
            [
                'model'=>$model,
                'introModel'=>$introModel,
                'brands'=>$brands,
                'goodsCates'=>$goodsCates,
            ]);
    }
    //商品删除--逻辑删除(status字段改为0)
    public function actionDel($id){
        $goods=Goods::findOne($id);
        $goods->status=0;
        if($goods->save()){
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['goods/index']);
        }else{
            var_dump($goods->getErrors());
        }
    }
    //查看商品详情
    public function actionContent($id){
        $content=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('content',['content'=>$content]);
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
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}