<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>商品分类名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goodscates as $goodscate):?>
        <tr>
            <td><?=$goodscate->id?></td>
            <td><?=str_repeat(' - - ',$goodscate->depth).$goodscate->name
                ?>
            </td>
            <td><?=$goodscate->intro?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['goodscategory/del','id'=>$goodscate->id])?>
                <?=\yii\bootstrap\Html::a('修改',['goodscategory/edit','id'=>$goodscate->id])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php echo \yii\bootstrap\Html::a('添加分类',['goodscategory/add'])?> |
<?php echo \yii\bootstrap\Html::a('查看分类层级',['goodscategory/show'])?>