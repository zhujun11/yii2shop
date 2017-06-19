<?php
$form=\yii\bootstrap\ActiveForm::begin([
    'method'=>'get',
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline'],
//    'layout'=>'horizontal',
]);
echo $form->field($serchForm,'name')->textInput(['placeholder'=>'商品名称'])->label(false);
echo $form->field($serchForm,'sn')->textInput(['placeholder'=>'商品货号'])->label(false);
echo $form->field($serchForm,'minprice')->textInput(['placeholder'=>'最低价格'])->label(false);
echo $form->field($serchForm,'maxprice')->textInput(['placeholder'=>'最高价格'])->label(false);
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info form-control','style'=>'margin-bottom:10px']);
\yii\bootstrap\ActiveForm::end();
?>


<table class="table table-bordered">
    <!-- <tr>
        <td colspan="11">
            <form action="<?=\yii\helpers\Url::toRoute(['goods/index']);?>" method="get">
            <div class="input-group" style="width: 400px;margin: auto">
                <input type="text" class="form-control" placeholder="商品名称关键词" aria-describedby="basic-addon1" name="keywords" id="key">
                <span class="input-group-addon " id="basic-addon2"><input type="submit" value="搜索" style="border:0; background: none"/></span>
            </div>
            </form>
        </td>

    </tr>-->
    <tr>
        <th>ID</th>
        <th>LOGO图片</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>市场价</th>
        <th>本店价</th>
        <th>商品分类</th>
        <th>商品品牌</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($allGoods as $goods):?>
    <tr>
        <td><?=$goods->id?></td>
        <td><?=\yii\bootstrap\Html::img($goods->logo,['width'=>60])?></td>
        <td><?=$goods->name?></td>
        <td><?=$goods->sn?></td>
        <td><?=$goods->market_price?></td>
        <td><?=$goods->shop_price?></td>
        <td><?=$goods->goodsCategory->name?></td>
        <td><?=$goods->brand->name?></td>
        <td><?=$goods->stock?></td>
        <td><?=\backend\models\Goods::$allSale[$goods->is_on_sale];?></td>
        <td><?=\backend\models\Goods::$allStatus[$goods->status]?></td>
        <td>
            <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('修改',['goods/edit','id'=>$goods->id],['class'=>'btn btn-warning btn-xs']);}?>
            <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('删除',['goods/del','id'=>$goods->id],['class'=>'btn btn-danger btn-xs']);}?><br/>
            <?=\yii\bootstrap\Html::a('详情',['goods/content','id'=>$goods->id],['class'=>'btn btn-info btn-xs'])?>
            <?=\yii\bootstrap\Html::a('相册',['goodsimg/index','id'=>$goods->id],['class'=>'btn btn-info btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('添加商品',['goods/add']);}?>
<?php
/*
use yii\web\JsExpression;

$url=yii\helpers\Json::encode(\Yii::$app->request->baseUrl.'/goods/index');
$js=new JsExpression(
    <<<JS
    
        $('#key').change(function() {
            var data={'keywords':$('#key').val()};
          $.ajax({
                url:{$url},
                type:'get',
                data:data})
          });
JS

);
$this->registerJs($js)
*/
?>

