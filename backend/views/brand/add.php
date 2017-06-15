<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/8
 * Time: 15:40
 */
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        alert(data.msg);
    } else {
        console.log(data.fileUrl);
        $('#new_logo').attr('src',data.fileUrl).show();
        $('#brand-logo').val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($model->logo){
    echo \yii\bootstrap\Html::img($model->logo,['width'=>100,'id'=>'new_logo']);
}else{
    echo \yii\bootstrap\Html::img('',['style'=>'display:none','width'=>100,'id'=>'new_logo']);
}
echo $form->field($model,'sort');
echo $form->field($model,'status')->inline()->radioList(\backend\models\Brand::$allStatus);
echo \yii\bootstrap\Html::submitButton('提交信息',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();