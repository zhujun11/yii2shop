<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/11
 * Time: 10:54
 */
namespace backend\models;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

class GoodsCategoryQuery extends ActiveQuery{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}