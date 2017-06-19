<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/16
 * Time: 14:26
 */
namespace backend\models;
use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{
    public $name;
    public $description;

    public function rules(){
        return [
          [['name','description'],'required']
        ];
    }
    public function attributeLabels(){
        return [
          'name'=>'权限名称',
          'description'=>'权限描述',
        ];
    }
    //添加权限方法
    public function addPermission(){
        //判断权限是否已存在
        $authManager=\Yii::$app->authManager;
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');

        }else{
            $permission=$authManager->createPermission($this->name);
            $permission->description=$this->description;
            return $authManager->add($permission);

        }
        return false;
    }
    //修改权限时,给模型加载数据
    public function loadData(Permission $permission){//加上参数约束,过滤数据
        $this->name=$permission->name;
        $this->description=$permission->description;
    }
    //实现修改权限更新数据的方法
    public function updatePermission($name){
        $authManager=\Yii::$app->authManager;
        //获取需要更新的权限对象
        $permission=$authManager->getPermission($name);
        //判断更新后的权限是否存在
        if($name!=$this->name && $authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            $permission->name=$this->name;
            $permission->description=$this->description;
            return $authManager->update($name,$permission);
        }
        return false;
    }
}