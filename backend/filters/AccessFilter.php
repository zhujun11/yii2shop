<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/16
 * Time: 19:50
 */
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;

class AccessFilter extends ActionFilter{
    public function beforeAction($action){
        if (!\Yii::$app->user->can($action->uniqueId)){
            //判断是否已登录
            if(\Yii::$app->user->isGuest){
                return $action->controller->redirect(\Yii::$app->user->loginUrl);
            }
            throw new HttpException(403,'您无权访问');
            return false;
        }
        return parent::beforeAction($action);
    }
}