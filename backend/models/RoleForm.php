<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/16
 * Time: 15:50
 */
namespace backend\models;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions=[];

    public function rules(){
        return [
            [['name','description'],'required'],
            ['permissions','safe']
        ];
    }

    public function attributeLabels(){
        return [
            'name'=>'角色名称',
            'description'=>'角色描述',
            'permissions'=>'权限',
        ];
    }
    //静态方法获取所有权限
    public static function getPermissionsOptions(){
        $permissionOptions=ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','description');
        return $permissionOptions;
    }
    //实现添加角色数据的方法
    public function addRole(){
        $authManager=\Yii::$app->authManager;
        //判断角色是否已存在
        if($authManager->getRole($this->name)){
            //存在
            $this->addError('name','角色已存在');
        }else{
            //创建一个角色
            $role=$authManager->createRole($this->name);
            $role->description=$this->description;
            //角色关联权限---在保证角色添加成功
            if($authManager->add($role)){
                //因为权限数据是数组形式,所以用遍历
                foreach ($this->permissions as $permissionName){
                    //获取权限对象
                    $permission=$authManager->getPermission($permissionName);
                    //关联权限
                    if($permission) $authManager->addChild($role,$permission);

                }
                return true;
            }
        }
        return false;
    }
    //对修改时的表单加载旧数据
    public function loadData(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        //通过角色名称关联获取权限
        $permissions=\Yii::$app->authManager->getPermissionsByRole($role->name);
//        var_dump($role->name);exit;
        foreach ($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }
    //更新角色数据
    public function updateRole($name){
        $authManager=\Yii::$app->authManager;
        //获取更新对象
        $role=$authManager->getRole($name);
        $role->name=$this->name;
        $role->description=$this->description;
        //判断角色名称修改后是否已存在
        if ($name!=$this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色名已存在');
        }else{
            //更新角色
            if($authManager->update($name,$role)){
                //关联角色权限---清空旧权限
                $authManager->removeChildren($role);
                foreach ($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission){
                        $authManager->addChild($role,$permission);//关联新权限
                    }
                }
                return true;
            }
        }
        return false;
    }
}