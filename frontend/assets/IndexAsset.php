<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/19
 * Time: 17:25
 */
namespace frontend\assets;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/index.css',
        'style/bottomnav.css',
        'style/footer.css',
    ];
    public $js = [
//        'js/jquery-1.8.3.min.js',
        'js/header.js',
        'js/index.js',
    ];
    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}