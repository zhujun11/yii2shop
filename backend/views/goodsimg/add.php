<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/13
 * Time: 18:12
 */
use yii\web\JsExpression;
$form=\yii\bootstrap\ActiveForm::begin();
//uploadify插件开始--图片上传
echo $form->field($model,'url[]')->hiddenInput();
echo '<div id="box"></div>';
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'options' => ['multiple' => true],
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
//        $('#new_logo').attr('src',data.fileUrl).show();
         var html="<input type='hidden' name='url[]' value='"+data.fileUrl+"' class='url'/>";
            $(html).appendTo('#box');
            
            
        <!--$('.form-control').val(data.fileUrl);-->
        
    }
}
EOF
        ),
    ]
]);
//uploadify插件结束--图片上传
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>





