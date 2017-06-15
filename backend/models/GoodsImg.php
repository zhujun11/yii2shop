<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/13
 * Time: 17:05
 */
namespace backend\models;
use yii\db\ActiveRecord;

class GoodsImg extends ActiveRecord{

    //和商品的关系
    public function getGoods(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
    public static function tableName()
    {
        return 'goodsimg';
    }
    public function rules(){
        return [
            ['url','string','max' => 255]
        ];
    }
    public function attributeLabels(){
        return [
            'url'=>'商品图片'
        ];
    }
}