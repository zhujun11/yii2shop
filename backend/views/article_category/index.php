<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>类型</th>
        <th>操作</th>
    </tr>
    <?php foreach($cates as $cate):?>
        <tr>
            <td><?=$cate->id?></td>
            <td><?=$cate->name?></td>
            <td><?=$cate->intro?></td>
            <td><?=$cate->sort?></td>
            <td><?=\backend\models\ArticleCategory::$allstatus[$cate->status];?></td>
            <td><?=\backend\models\ArticleCategory::$allcats[$cate->is_help];?></td>
            <td>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('修改',['article_category/edit','id'=>$cate->id]);}?>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('删除',['article_category/del','id'=>$cate->id]);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('添加文章分类',['article_category/add']);}?>