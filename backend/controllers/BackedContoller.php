<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/18
 * Time: 11:21
 */
namespace backend\controllers;

use backend\filters\AccessFilter;
use yii\web\Controller;

class BackedContoller extends Controller{
//    配置RBAC(基于角色的存取控制)权限控制
//    1.配置ACF---存取控制过滤器
    public function behaviors(){
        return [
            'accessFilter'=>[
                'class'=>AccessFilter::className(),
            ]
        ];
    }
}