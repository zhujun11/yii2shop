<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/20
 * Time: 17:41
 */
namespace frontend\assets;

use yii\web\AssetBundle;

class RegionAsset extends AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/home.css',
        'style/address.css',
        'style/bottomnav.css',
        'style/footer.css',
        'style/list.css',
        'style/common.css',
        'style/goods.css',
    ];
    public $js = [
//        'js/jquery-1.8.3.min.js',
        'js/header.js',
        'js/home.js',
        'js/list.js',
        'js/goods.js',
//        'js/jqzoom-core.js',
    ];
    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}