<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>路由</th>
        <th>操作</th>
    </tr>
    <?php foreach ($menus as $menu):?>
        <tr>
            <td><?=$menu->id?></td>
            <td><?=$menu->label?></td>
            <td><?=$menu->url?></td>
            <td>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('修改',['menu/edit','id'=>$menu->id]);}?>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('删除',['menu/del','id'=>$menu->id]);}?>
            </td>
        </tr>
    <?php if($menu->children) {foreach ($menu->children as $child):?>
            <tr>
                <td><?=$child->id?></td>
                <td><?=' - - -'.$child->label?></td>
                <td><?=$child->url?></td>
                <td>
                    <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('修改',['menu/edit','id'=>$child->id]);}?>
                    <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('删除',['menu/del','id'=>$child->id]);}?>
                </td>
            </tr>
    <?php endforeach;}?>
    <?php endforeach;?>

</table>

    <div style="text-align: center">
        <?php echo \yii\widgets\LinkPager::widget(
            [
                'pagination'=>$page,
                'prevPageCssClass'=>'上一页',
                'nextPageCssClass'=>'下一页',
            ]
        )?>
    </div>

<?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('添加菜单',['menu/add']);}?>