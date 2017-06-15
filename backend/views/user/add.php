<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/14
 * Time: 10:54
 */
$form=\yii\bootstrap\ActiveForm::begin([
    'options'=>['class'=>'form-horizontal'],
    'layout'=>'horizontal'
]);
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'topassword')->passwordInput();
echo $form->field($model,'email');
//echo $form->field($model,'status')->inline()->radioList(\backend\models\User::$allStatus);
echo \yii\bootstrap\Html::submitButton('确认提交',['class'=>'btn btn-info col-sm-offset-3']);
\yii\bootstrap\ActiveForm::end();