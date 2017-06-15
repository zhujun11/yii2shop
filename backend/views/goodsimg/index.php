
<h2>商品<span style="color: red"><?=\backend\models\Goods::findOne($goods_id)->name;?></span>的相册</h2>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>商品图片</th>
        <th>操作</th>
    </tr>
    <?php foreach ($imgs as $img):?>
        <tr>
            <td><?=$img->id?></td>
            <td><?=\yii\bootstrap\Html::img($img->url,['width'=>200])?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['goodsimg/del','id'=>$img->id])?>
                <?=\yii\bootstrap\Html::a('修改',['goodsimg/edit','id'=>$img->id])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\bootstrap\Html::a('添加图片',['goodsimg/add','goodsid'=>$goods_id])?>&emsp;
<?=\yii\bootstrap\Html::a('返回商品列表',['goods/index'])?>