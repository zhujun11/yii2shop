<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/14
 * Time: 14:40
 */
$form=\yii\bootstrap\ActiveForm::begin([
//    'options'=>['class'=>'form-horizontal'],
    'layout'=>'horizontal'
]);
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'user/captcha',
    'template'=>'<div class="row">
                <div class="col-sm-1">{image}</div>
                <div class="col-sm-3 col-xs-offset-2">{input}</div>
                </div>'
]);
echo $form->field($model,'autoLogin')->checkbox();
echo \yii\bootstrap\Html::submitButton('确认登录',['class'=>'btn btn-info col-sm-offset-3']);
\yii\bootstrap\ActiveForm::end();