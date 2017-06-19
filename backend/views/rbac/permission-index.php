<?php
/* @var $this yii\web\View */
?>
<table class="table table-bordered">
    <tr>
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($permissions as $permission):?>
        <tr>
            <td><?=$permission->name?></td>
            <td><?=$permission->description?></td>
            <td>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('修改',['rbac/permission-edit','name'=>$permission->name],['class'=>'btn btn-warning btn-xs']);}?>                                   <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('删除',['rbac/permission-del','name'=>$permission->name],['class'=>'btn btn-danger btn-xs']);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('添加权限',['rbac/permission-add']);}?>
