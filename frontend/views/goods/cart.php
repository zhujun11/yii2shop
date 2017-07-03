<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/23
 * Time: 16:17
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/style/cart.css');
$this->registerJsFile('@web/js/cart1.js',['depends'=>\yii\web\JqueryAsset::className()])

?>
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($goods_info as $goods):?>
        <tr data-goods_id="<?=$goods['id']?>">
            <td class="col1"><?=\yii\helpers\Html::a(\yii\helpers\Html::img('http://admin.yii2shop.com'.$goods['logo'],['goods/content','goods_id'=>$goods['id']]))?>  <strong><?=\yii\helpers\Html::a($goods['name'],['goods/content','goods_id'=>$goods['id']])?></strong></td>
            <td class="col3">￥<span><?=$goods['shop_price']?></span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="amount" value="<?=$goods['amount']?>" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <td class="col5">￥<span class="subtotal"><?=($goods['amount']*$goods['shop_price'])?></span></td>
            <td class="col6"><a href="javascript:;" class="del_cart">删除</a></td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total"><?=$total?></span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <?=\yii\helpers\Html::a('继续购物',['home/index'],['class'=>'continue'])?>
        <?=\yii\helpers\Html::a('去结算',['home/order'],['class'=>'checkout'])?>
    </div>
</div>
<!-- 主体部分 end -->

<?php
/**
 * @var $this \yii\web\View
 */
$url=\yii\helpers\Url::to(['goods/update-cart']);
$token= Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        //监听修改数量事件
    $(".reduce_num,.add_num").click(function() {
        var amount=$(this).closest('tr').find('.amount').val();
        var goods_id=$(this).closest('tr').attr('data-goods_id');
        $.post("$url",{goods_id:goods_id,amount:amount,"_csrf-frontend":"$token"});
        })
        //监听删除事件---将数量该为0
    $(".del_cart").click(function() {
        if(confirm('确定删除选中商品吗?')){
            var goods_id=$(this).closest('tr').attr('data-goods_id');
            $.post("$url",{goods_id:goods_id,amount:0,"_csrf-frontend":"$token"});
            //被删除记录的金额
            var price=$(this).closest('tr').find('.subtotal').text();
            //删除页面记录
            $(this).closest('tr').remove();
            
            var total=Number($("#total").text())-Number(price);
            $("#total").text(total);
            }
    })
      
JS

))
?>

