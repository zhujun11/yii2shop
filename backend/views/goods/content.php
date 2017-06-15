<table class="table">
    <tr>
        <th style="text-align: center"><h2><?=$content->goods->name?></h2></th>
    </tr>
    <tr>
        <td><?=$content->content?></td>
    </tr>
</table>
<?php echo \yii\bootstrap\Html::a('返回商品列表',['goods/index'])?>