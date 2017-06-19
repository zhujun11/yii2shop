<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/18
 * Time: 11:27
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label');
echo $form->field($model,'url');
echo $form->field($model,'parent_id')->dropDownList($options,['prompt'=>'=请选择上级菜单=']);
echo $form->field($model,'short');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
