<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    //文章列表
    public function actionIndex()
    {
        $query=Article::find();
        $page=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>5,
        ]);
        $articles=$query->offset($page->offset)->limit($page->limit)->where('status >=0')->all();
        return $this->render('index',['articles'=>$articles,'page'=>$page]);
    }
    //发表文章
    public function actionAdd(){
        $model_list=new Article();
        $model_content=new ArticleDetail();
        $cates=['请选择分类'];
        $categorys=ArticleCategory::find()->all();
        foreach($categorys as $category){
            $cates[$category->id]=$category->name;
        }
        $request=new Request();
        if($request->isPost){
            $model_content->load($request->post());
            $model_list->load($request->post());
            if($model_list->validate()&&$model_content->validate()){
                $model_list->create_time=time();
                $model_content->save();
                $model_list->save();
                \Yii::$app->session->setFlash('success','发表成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model_content->getErrors());
                var_dump($model_list->getErrors());
            }
        }

        return $this->render('add',['model_list'=>$model_list,'model_content'=>$model_content,'cates'=>$cates]);
    }
    //修改文章
    public function actionEdit($id){
        $model_list=Article::findOne($id);
        $model_content=ArticleDetail::findOne($id);
        $cates=['请选择分类'];
        $categorys=ArticleCategory::find()->all();
        foreach($categorys as $category){
            $cates[$category->id]=$category->name;
        }
        $request=new Request();
        if($request->isPost){
            $model_content->load($request->post());
            $model_list->load($request->post());
            if($model_list->validate()&&$model_content->validate()){
                $model_content->save();
                $model_list->save();
                \Yii::$app->session->setFlash('success','发表成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model_content->getErrors());
                var_dump($model_list->getErrors());
            }
        }

        return $this->render('add',['model_list'=>$model_list,'model_content'=>$model_content,'cates'=>$cates]);
    }

    //删除文章--逻辑删除,status=-1;
    public function actionDel($id){
        $model=Article::findOne($id);
        $model->status=-1;
        if($model->save()){
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['article/index']);
        }else{
            var_dump($model->getErrors());
        }
    }
    //查看详情
    public function actionSdetail($id){
        $content=ArticleDetail::findOne($id);
        $article=Article::findOne($id);
        return $this->render('sdetail',['content'=>$content,'article'=>$article]);
    }
}
