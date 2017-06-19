<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/18
 * Time: 11:25
 */
namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class MenuController extends BackedContoller {

    //添加菜单
    public function actionAdd(){
        $model=new Menu();
        $lists=Menu::find()->asArray()->where('parent_id=0')->all();
        $options=ArrayHelper::merge([['id'=>0,'label'=>'顶级菜单','parent_id'=>0]],$lists);
        $options=ArrayHelper::map($options,'id','label');
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model,'options'=>$options]);
    }
    //菜单列表
    public function actionIndex(){
        $query=Menu::find();
        $page=new Pagination([
            'totalCount'=>$query->where(['parent_id'=>0])->count(),
            'defaultPageSize'=>4,
        ]);
        $menus=$query->offset($page->offset)->limit($page->limit)->where(['parent_id'=>0])->all();
        return $this->render('index',['menus'=>$menus,'page'=>$page]);
    }
    //修改菜单
    public function actionEdit($id){
        $model=Menu::findOne($id);
        $lists=Menu::find()->asArray()->where('parent_id=0')->all();
        $options=ArrayHelper::merge([['id'=>0,'label'=>'顶级菜单','parent_id'=>0]],$lists);
        $options=ArrayHelper::map($options,'id','label');
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['menu/index']);
        }
        return $this->render('add',['model'=>$model,'options'=>$options]);
    }
    public function actionDel($id)
    {
        $menu = Menu::findOne($id);

        $childmenu=Menu::find()->where('parent_id='.$id)->one();
//        var_dump($childmenu);exit;
        if($childmenu!=null){
            \Yii::$app->session->setFlash('success','存在二级菜单,无法删除');
            return $this->redirect(['menu/index']);
        }
        $menu->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['menu/index']);
    }
}