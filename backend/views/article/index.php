<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->articleCategory->name;?></td>
            <td><?=$article->sort?></td>
            <td><?=\backend\models\Article::$allStatus[$article->status];?></td>
            <td><?=date("Y-d-m H:i:s",$article->create_time);?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id])?>
                <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$article->id])?>
                <?=\yii\bootstrap\Html::a('查看详细',['article/sdetail','id'=>$article->id])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\bootstrap\Html::a('发表文章',['article/add'])?>