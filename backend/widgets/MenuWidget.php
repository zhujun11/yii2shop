<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/18
 * Time: 9:51
 */
namespace backend\widgets;

use backend\models\Menu;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use yii;
class MenuWidget extends Widget{
    public function run(){

            NavBar::begin([
                'brandLabel' => '惊西商城',
                'brandUrl' =>['goods/index'],
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
        $menuItems = [
            ['label' => '首页', 'url' => ['backend/index']],

        ];
        if (Yii::$app->user->isGuest) {
//        var_dump(\Yii::$app->user->isGuest);
            $menuItems[] = ['label' => '登录', 'url' => ['user/login']];
        } else {
//            $menuItems[]=['label'=>'品牌管理','items'=>[
//                ['label'=>'品牌添加','url'=>['brand/add']],
//            ]];
            //获取所有一级菜单
            $menus=Menu::findAll(['parent_id'=>0]);
            //遍历一级菜单,查找二级菜单
            foreach ($menus as $menu){
                $items=['label'=>$menu->label,'items'=>[]];
                foreach ($menu->children as $child){
                    if (Yii::$app->user->can($child->url)){

                    $items['items'][]=['label'=>$child->label,'url'=>[$child->url]];
                    }
                }
                if (!empty($items['items'])){

                $menuItems[]=$items;
                }
            }


            $menuItems[] = ['label' => '注销('.Yii::$app->user->identity->username.')', 'url' => ['user/logout']];
        }
        echo yii\bootstrap\Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
}