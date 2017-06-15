<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/12
 * Time: 11:38
 */
use yii\web\JsExpression;
use \kucha\ueditor\UEditor;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';//zTree插件
echo $form->field($model,'brand_id')->dropDownList($brands);
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale')->inline()->radioList(\backend\models\Goods::$allSale);
echo $form->field($model,'status')->inline()->radioList(\backend\models\Goods::$allStatus);
echo $form->field($model,'sort');
//uploadify插件开始--图片上传
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
//        console.log(data.fileUrl);
        $('#new_logo').attr('src',data.fileUrl).show();
        $('#goods-logo').val(data.fileUrl);
        
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
//uploadify插件结束--图片上传


//echo $form->field($introModel,'content')->textarea();
echo $form->field($introModel,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions'=>[
        'initialFrameHeight'=>'200',
        'toolbars'=>[
            ['fullscreen', 'source', 'undo', 'redo', 'bold'],
            ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
        ]
    ]
]);



echo \yii\bootstrap\Html::submitButton('确认提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

//zTree插件开始----商品分类
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery-1.4.4.min.js');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes=yii\helpers\Json::encode($goodsCates);
$js=new \yii\web\JsExpression(
    <<<JS
        var zTreeObj;
            // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
            var setting = {
                data: {
                    simpleData: {
                        enable: true,
                        idKey: "id",
                        pIdKey: "parent_id",
                        rootPId: 0
                    }
                },
                callback: {
                    onClick: function(event, treeId, treeNode) {
                        $('#goods-goods_category_id').val(treeNode.id);
                        // console.debug(a.val())
                    }
                }
            };
            // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
            var zNodes = {$zNodes};//获取所有节点的
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            // zTreeObj.expandAll(true);//展开所有节点
            var node = zTreeObj.getNodeByParam("id",$('#goods-goods_category_id').val(),null);//根据parent_ID找到节点
            zTreeObj.selectNode(node);

JS
);
$this->registerJs($js)
//zTree插件结束----商品分类
?>