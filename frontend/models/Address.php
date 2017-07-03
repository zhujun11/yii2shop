<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property integer $member_id
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property string $address
 * @property string $tel
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    public $region;

    public function getProvince(){
        return $this->hasOne(Locations::className(),['id'=>'province_id']);
    }
    public function getCity(){
        return $this->hasOne(Locations::className(),['id'=>'city_id']);
    }
    public function getArea(){
        return $this->hasOne(Locations::className(),['id'=>'area_id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','address','tel'], 'required'],
            // 'province_id', 'city_id', 'area_id'
            [['member_id', 'province_id', 'city_id', 'area_id', 'status','region'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'member_id' => '账号ID',
            'province_id' => '省份ID',
            'city_id' => '城市ID',
            'area_id' => '区县ID',
            'address' => '详细地址',
            'tel' => '手机号',
            'status' => '设为默认地址',
            'region' => '所在地区',
        ];
    }

}
