<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GoodscategoryController extends \yii\web\Controller
{
    //商品分类列表
    public function actionIndex()
    {
        $goodsCates=GoodsCategory::find()->orderBy('tree,lft')->all();
//        $page=new Pagination([
//            'totalCount'=>$query->count(),
//            'defaultPageSize'=>10,
//        ]);
//        $goodsCates=$query->offset($page->offset)->limit($page->limit)->orderBy('tree,lft')->all();
        return $this->render('index',['goodscates'=>$goodsCates]);
    }
    //商品分类添加
    public function actionAdd(){
        $model=new GoodsCategory();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());

            if($model->validate()){
                if($model->parent_id){
                    //添加子分类
                   $parentmodel=GoodsCategory::findOne($model->parent_id) ;
                   $model->prependTo($parentmodel);

                }else{
                    //添加顶级分类

                    $model->makeRoot();
                }
//                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goodscategory/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        //获取已有的所有分类
        $option=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'option'=>$option]);
    }
    //修改商品分类
    public function actionEdit($id){
        $model=GoodsCategory::findOne($id);
        if($model==null){
            throw new NotFoundHttpException('分类存在');
        }
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());

            if($model->validate()){
                if($model->parent_id){
                    //添加子分类
                    $parentmodel=GoodsCategory::findOne($model->parent_id) ;
                    $model->prependTo($parentmodel);

                }else{
                    //修改顶级分类
                    if($model->getOldAttribute('parent_id')==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }
                }
//                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goodscategory/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        //获取已有的所有分类
        $option=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'option'=>$option]);
    }
    //删除--彻底
    public function actionDel($id){
        $goodscate=GoodsCategory::findOne($id);
        if($goodscate->delete()){
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['goodscategory/index']);
        }
    }
    //嵌套集合测试(商品无限极分类)
    public function actionTest(){
//        $firstone=new GoodsCategory();
//        $firstone->name='家用电器';
//        $firstone->parent_id='0';
//        $firstone->makeRoot();
//        $firstchild=new GoodsCategory();
//        $firstone=GoodsCategory::findOne(3);
//        var_dump($firstone);exit;
//        $firstchild->name='小家电';
//        $firstchild->parent_id=$firstone->id;
//        $firstchild->prependTo($firstone);
        $gootsCates=GoodsCategory::find()->asArray()->all();
        $cates=json_encode($gootsCates);
//        var_dump($cates);exit;
        return $this->renderPartial('test',['cates'=>$cates]);
    }
    //分类层级显示
    public function actionShow(){
        //获取数组形式分类数据
        $goodsCates=GoodsCategory::find()->asArray()->all();
        //调用视图,传输模型
        return $this->renderPartial('show',['goodsCates'=>$goodsCates]);
    }
}
