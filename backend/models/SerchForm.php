<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/13
 * Time: 21:24
 */
namespace backend\models;
use yii\base\Model;
use yii\db\ActiveQuery;

class SerchForm extends Model{
    public $name;
    public $sn;
    public $minprice;
    public $maxprice;
    public function rules(){
        return [
            [['sn','name'],'string'],
            [['maxprice','minprice'],'double']
        ];
    }
    public function attributeLabels(){
        return [
            'sn'=>'货号',
            'name'=>'商品名称',
            'minprice'=>'最低价格',
            'maxprice'=>'最高价格',
        ];
    }
    public function search(ActiveQuery $query){
        $this->load(\Yii::$app->request->get());
        if($this->name){
            $query->andWhere(['like','name',$this->name]);
        }
        if($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }
        if($this->maxprice){
            $query->andWhere(['<=','shop_price',$this->maxprice]);
        }
        if($this->minprice){
            $query->andWhere(['>=','shop_price',$this->minprice]);
        }

    }
}