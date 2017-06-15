<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/8
 * Time: 18:14
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'status')->inline()->radioList(\backend\models\ArticleCategory::$allstatus);
echo $form->field($model,'is_help')->inline()->radioList(\backend\models\ArticleCategory::$allcats);
echo \yii\bootstrap\Html::submitButton('提交信息',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
