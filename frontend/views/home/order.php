<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/25
 * Time: 10:13
 * @var $this \yii\web\View
 */
use yii\helpers\Html;

$this->registerCssFile('@web/style/fillin.css');
$this->registerJsFile('@web/js/cart2.js',['depends'=>\yii\web\JqueryAsset::className()]);
?>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="/home/index.html"><?=Html::img('@web/images/logo.png')?></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <p>
                    <?php foreach ($addresses as $address):?>
                    <input type="radio" value="<?=$address->id?>" name="address_id" class="address_id"/>
                    <?=$address->name.'&emsp;'.
                    $address->tel.'&emsp;'
                    .$address->province->name.'&emsp;'
                    .$address->city->name.'&emsp;'
                    .$address->area->name.'&emsp;'
                    .$address->address
                    ?>
                     </p>
                <?php endforeach;?>
                <p><?=Html::a('添加地址',['member/index'])?></p>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (\frontend\models\Order::$deliveries as $k=> $delivery):?>
                    <tr class="<?php if ($k==0) echo 'cur'?>" data-delivery_price="<?=$delivery['price']?>">
                        <td>
                            <input type="radio" name="delivery" <?php if ($k==0) echo 'checked';?> value="<?=$delivery['id']?>"/><?=$delivery['name']?>

                        </td>
                        <td>￥<?=$delivery['price']?></td>
                        <td><?=$delivery['intro']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$payments as $k=> $payment):?>
                    <tr class="<?php if ($k==0) echo 'cur'?>">
                        <td class="<?php if ($k==0) echo 'col1'?>">
                            <input type="radio" name="pay" value="<?=$payment['id']?>" />
                            <?=$payment['name']?>
                        </td>
                        <td class="col2"><?=$payment['intro']?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="<?=\yii\helpers\Url::to(['home/order'])?>" method="post">
                    <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken;?>"/>
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($carts as $cart):?>
                <tr data-cart_id="<?=$cart->id?>" class="cart_id">
                    <td class="col1">
                        <a href="">
                            <?=Html::img('http://admin.yii2shop.com'.$cart->goods->logo)?>
                        </a>
                        <strong>
                            <a href=""><?=$cart->goods->name?></a>
                        </strong></td>
                    <td class="col3">￥<?=$cart->goods->shop_price?></td>
                    <td class="col4"><?=$cart->amount?></td>
                    <td class="col5">￥<span><?=($cart->amount*$cart->goods->shop_price) ?></span></td>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span>4 件商品，总商品金额：</span>
                                <em>￥5316.00</em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥240.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="delivery_price"></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em>￥5076.00</em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <a href="javascript:;" id="sub_order"><span>提交订单</span></a>
        <p>应付总额：<strong>￥<span class="total_money"></span></strong></p>

    </div>
</div>
<?php
$url=\yii\helpers\Url::to(['home/add-order']);
$urlTo=\yii\helpers\Url::to(['home/order1']);
$urlReturn=\yii\helpers\Url::to(['goods/cart']);
$token=Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        
    var delivery=$('.delivery_select input:checked');
    var money=0;//商品总金额
    $('.goods .cart_id .col5 ').find("span").each(function(i,v) {
        money+=Number($(v).text());
    });
    var total=Number(money)+Number(delivery.closest('tr').attr('data-delivery_price'));
    $('#sub_order').click(function() {
        
        var cart_ids=[];
        if($('.cart_id').length==0){
            alert('还未选择商品');
            return;
        }else 
        $('.cart_id').each(function(i,v) {
            cart_ids[i]=$(v).attr('data-cart_id');
        });
        if(cart_ids.length==1){
            cart_ids=cart_ids[0];
        }else {
            
        cart_ids=cart_ids.join();
        }
        var payment=$('.pay_select input:checked');
        var address=$('.address_info input:checked');
        if(delivery.length==0){
            alert('送货方式未选择');
            return;
        }
        if(payment.length==0){
            alert('支付方式未选择');
            return;
        }
        if(address.length==0){
            alert('地址未选择');
            return;
        }
        var data={
            delivery_id:delivery.val(),
            payment_id:payment.val(),
            address_id:address.val(),
            cart_ids:cart_ids,
            total:total,
            "_csrf-frontend":"$token"
        };
        $.post("$url",data,function(rs) {
            if(rs=='success'){
                window.location.href="$urlTo";
            }else {
                if (confirm(rs+',返回购物车')){
                   window.location.href="$urlReturn"; 
                }
            }
        })
    });
    
    $('.total_money').text(total);
    $('#delivery_price').text('￥'+delivery.closest('tr').attr('data-delivery_price'))
JS

))

?>