<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'yii2惊喜商城',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => '后台首页', 'url' => ['/backend/index']],

    ];
    if (Yii::$app->user->isGuest) {
//        var_dump(\Yii::$app->user->isGuest);
        $menuItems[] = ['label' => '管理员登录', 'url' => ['/login/login']];
    } else {
        $menuItems=[
            ['label' => '文章', 'url' => ['/article/index']],
            ['label' => '文章分类', 'url' => ['/article_category/index']],
            ['label' => '商品', 'url' => ['/goods/index']],
            ['label' => '商品分类', 'url' => ['/goodscategory/index']],
            ['label' => '品牌', 'url' => ['/brand/index']],
            ['label' => '管理员', 'url' => ['/user/index']],
        ];
        $menuItems[] = '<li>'
            . Html::beginForm(['/login/logout'], 'post')
            . Html::submitButton(
                '( 已登录管理员: ' . Yii::$app->user->identity->username . ' ) 退出 ',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
