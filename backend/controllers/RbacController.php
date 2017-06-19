<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class RbacController extends BackedContoller
{
    //权限增删查改开始
    public function actionPermissionIndex()
    {
        $authManager=\Yii::$app->authManager;
        $permissions=$authManager->getPermissions();
//        $page=new Pagination([
//            'totalCount'=>$authManager->
//        ]);
        return $this->render('permission-index',['permissions'=>$permissions]);
    }

    //添加权限
    public function actionPermissionAdd(){
        $model=new PermissionForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addPermission()){//调用模型中的自定义添加权限方法
                \Yii::$app->session->setFlash('success','添加权限成功');
                return $this->redirect(['permission-index']);
            }
        }
        return $this->render('permission-add',['model'=>$model]);
    }
    //修改权限
    public function actionPermissionEdit($name){
        $model=new PermissionForm();
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        if ($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model->loadData($permission);//调用模型方法加载数据
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //调用表单模型的updatePermission方法实现数据更新
            if($model->updatePermission($name)){
                \Yii::$app->session->setFlash('success','修改权限成功');
                return $this->redirect(['permission-index']);
            }
        }
        //更新数据
        return $this->render('permission-add',['model'=>$model]);
    }
    //删除权限
    public function actionPermissionDel($name){
        $permission=\Yii::$app->authManager->getPermission($name);
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','删除权限成功');
        return $this->redirect(['permission-index']);
    }
    //权限增删查改结束


    //角色增删查改开始

    //添加角色
    public function actionRoleAdd(){
        $model=new RoleForm();
        //保存添加的角色
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //调用模型的自定义添加数据方法
            if ($model->addRole()){
                \Yii::$app->session->setFlash('success','添加角色成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role-add',['model'=>$model]);
    }
    //显示角色列表
    public function actionRoleIndex(){
        //h获取所有角色
        $roles=\Yii::$app->authManager->getRoles();
        return $this->render('role-index',['roles'=>$roles]);
    }
    //修改角色
    public function actionRoleEdit($name){
        $model=new RoleForm();
        $authManager=\Yii::$app->authManager;
        //获取旧数据
        $role=$authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        //对模型加载旧数据
        $model->loadData($role);
        //更新数据
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            //调用自定义更新方法
            if($model->updateRole($name)){
                \Yii::$app->session->setFlash('success','修改角色成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role-add',['model'=>$model]);
    }
    //删除角色
    public function actionRoleDel($name){
        $role=\Yii::$app->authManager->getRole($name);
        \Yii::$app->authManager->remove($role);
        \Yii::$app->session->setFlash('success','删除角色成功');
        return $this->redirect(['role-index']);
    }

    //角色增删查改结束

}
