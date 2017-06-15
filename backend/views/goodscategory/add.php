<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/10
 * Time: 10:15
 * @var $this\Yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('确认提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

//加载zTree相关js.css等
//<link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
//<script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>
//<script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery-1.4.4.min.js');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes=yii\helpers\Json::encode($option);
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
                        $('#goodscategory-parent_id').val(treeNode.id);
                    }
                }
            };
            // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
            var zNodes = {$zNodes};//获取所有节点的
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            zTreeObj.expandAll(true);//展开所有节点
            var node = zTreeObj.getNodeByParam("id",$('#goodscategory-parent_id').val(),null);//根据parent_ID找到节点
            zTreeObj.selectNode(node);

JS
);
$this->registerJs($js)
?>

