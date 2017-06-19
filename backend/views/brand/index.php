<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>品牌LOGO</th>
        <th>品牌名称</th>
        <th>品牌简介</th>
        <th>品牌排序</th>
        <th>品牌状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($brands as $brand):?>
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->logo ? \yii\bootstrap\Html::img($brand->logo,['width'=>60]):'';?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?=$brand->sort?></td>
            <td><?=\backend\models\Brand::$allStatus[$brand->status];?></td>
            <td>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id]);}?>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('删除',['brand/del','id'=>$brand->id]);}?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="7" style="text-align: center">
            <?php echo \yii\widgets\LinkPager::widget(
                    [
                        'pagination'=>$page,
                        'prevPageCssClass'=>'上一页',
                        'nextPageCssClass'=>'下一页',
                    ]
            )?>
        </td>
    </tr>
</table>
<?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('添加品牌',['brand/add']);}?>
