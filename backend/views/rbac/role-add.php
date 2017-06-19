<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/16
 * Time: 16:11
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description');
echo $form->field($model,'permissions')->inline()->checkboxList(\backend\models\RoleForm::getPermissionsOptions());
echo \yii\bootstrap\Html::submitButton('确认提交',['class'=>'btn btn-info ']);
\yii\bootstrap\ActiveForm::end();