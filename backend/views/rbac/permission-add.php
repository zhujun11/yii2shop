<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/16
 * Time: 14:34
 */
$form=\yii\bootstrap\ActiveForm::begin([
    'layout'=>'horizontal',
]);
echo $form->field($model,'name');
echo $form->field($model,'description');
echo \yii\bootstrap\Html::submitButton('确认提交',['class'=>'btn btn-info col-xs-offset-2']);
\yii\bootstrap\ActiveForm::end();