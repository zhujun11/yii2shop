<?php
/* @var $this yii\web\View */
//
//$js=new \yii\web\JsExpression(
//        <<<JS
//        $('.btn-danger').click(function() {
//            confirm('确定删除吗')
//        })
//JS
//
//);
//$this->registerJs($js);
?>
<h1>管理员列表</h1>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>状态</th>
        <th>邮箱</th>
        <th>建号时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach ($users as $user):?>
        <tr>
            <td><?=$user->id?></td>
            <td><?=$user->username?></td>
            <td><?=\backend\models\User::$allStatus[$user->status];?></td>
            <td><?=$user->email?></td>
            <td><?=$user->created_at?></td>
            <td><?=date("Y-m-d H:i:s",$user->last_time)?></td>
            <td><?=$user->last_ip?></td>
            <td>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('修改',['user/edit','id'=>$user->id],['class'=>'btn btn-warning btn-xs']);}?>
                <?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('彻底删除',['user/del','id'=>$user->id],['class'=>'btn btn-danger btn-xs']);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php if(Yii::$app->user->can('user/edit')){echo \yii\bootstrap\Html::a('添加管理员',['user/add']);}?>
