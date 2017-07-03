<?php

namespace backend\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "goodscategory".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }
    public function getParent(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }

    //查找商品分类
    public static function getCategory($depth=0,$parent_id=0){
        $goodsCategories=GoodsCategory::find()->where(['depth'=>$depth,'parent_id'=>$parent_id])->all();
        return $goodsCategories;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'parent_id','name'], 'required'],
//            ['name','unique','message'=>'名称不能重复'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => '树ID',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '商品分类名称',
            'parent_id' => '上级分类',
            'intro' => '简介',
        ];
    }
    //嵌套集合插件
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
}
