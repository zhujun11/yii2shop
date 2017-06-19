<table class="table table-bordered">
    <tr>
        <th>角色名称</th>
        <th>角色描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('修改',['rbac/role-edit','name'=>$role->name],['class'=>'btn btn-warning btn-xs']);}?>                                   <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('删除',['rbac/role-del','name'=>$role->name],['class'=>'btn btn-danger btn-xs']);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('添加角色',['rbac/role-add']);}?>