<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;

class Article_categoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query=ArticleCategory::find();
        $page=new Pagination(
            [
                'totalCount'=>$query->count(),
                'defaultPageSize'=>5,
            ]
        );
        $cates=$query->offset($page->offset)->limit($page->limit)->where('status >=0')->all();
        return $this->render('index',['cates'=>$cates,'page'=>$page]);
    }
    public function actionAdd(){
        $model=new ArticleCategory();
        $model->status=1;
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article_category/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model=ArticleCategory::findOne($id);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article_category/index']);
            }else{
                var_dump($model->getErrors());
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDel($id){
        $model=ArticleCategory::findOne($id);
        $model->status=-1;
        if($model->save()){
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['article_category/index']);
        }
    }
}
