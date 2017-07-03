

<table class="table table-bordered">
    <tr>
        <th>商品图片</th>
        <th>商品名称</th>
        <th>商品价格</th>
        <th>商品数量</th>
        <th>商品小计</th>
    </tr>
    <?php foreach ($allGoods as $goods):?>
    <tr>
        <td><?=\yii\bootstrap\Html::img($goods->logo,[ 'width'=>"100px"])?></td>
        <td><?=$goods->goods_name?></td>
        <td><?=$goods->price?></td>
        <td><?=$goods->amount?></td>
        <td><?=$goods->total?></td>
    </tr>
    <?php endforeach;?>
</table>
