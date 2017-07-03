<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/25
 * Time: 17:05
 * @var $this \yii\web\View
 */
use yii\helpers\Html;

$this->registerCssFile('@web/style/success.css')
?>
<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="/home/index.html"><?=Html::img('@web/images/logo.png')?></a></h2>
        <div class="flow fr flow3">
            <ul>
                <li>1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li class="cur">3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->
<!-- 主体部分 start -->
<div class="success w990 bc mt15">
    <div class="success_hd">
        <h2>订单提交成功</h2>
    </div>
    <div class="success_bd">
        <p><span></span>订单提交成功，我们将及时为您处理</p>

        <p class="message">完成支付后，你可以 <?=Html::a('查看订单状态',['home/order1'])?>  <?=Html::a('继续购物',['home/index'])?> <a href="">问题反馈</a></p>
    </div>
</div>
<!-- 主体部分 end -->
