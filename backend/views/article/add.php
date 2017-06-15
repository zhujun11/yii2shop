<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/8
 * Time: 18:59
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model_list,'name');
echo $form->field($model_list,'intro')->textarea();
echo $form->field($model_list,'article_category_id')->dropDownList($cates);
echo $form->field($model_content,'content')->textarea();
echo $form->field($model_list,'sort');
echo $form->field($model_list,'status')->inline()->radioList(\backend\models\Article::$allStatus);
echo \yii\bootstrap\Html::submitButton('提交信息',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

