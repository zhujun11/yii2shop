<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2017/6/20
 * Time: 17:54
 */

?>


<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10" style="width: 990px">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php foreach ($addresses as $address):?>
            <dl>
<!--                <dt>1.许坤 北京市 昌平区 仙人跳区 仙人跳大街 17002810530 </dt>-->
                <dt><?php echo '姓名: '.$address->name.' 详细地址: ';if($address->province_id){echo\frontend\models\Locations::findOne($address->province_id)->name. \frontend\models\Locations::findOne($address->city_id)->name . \frontend\models\Locations::findOne($address->area_id)->name;};
                    echo $address->address.' 手机号: '.$address->tel?></dt>
                <dd>
                    <?=\yii\helpers\Html::a('修改',['member/edit','id'=>$address->id])?>
                    <?=\yii\helpers\Html::a('删除',['member/del','id'=>$address->id])?>
                    <?=$address->status ? '默认地址':\yii\helpers\Html::a('设为默认地址',['member/default','id'=>$address->id]);?>
                </dd>
            </dl>

            <?php endforeach;?>
        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php
                $form=\yii\widgets\ActiveForm::begin(
                        [
                            'fieldConfig'=>[
                                'options'=>[
                                        'tag'=>'li'
                                ]
                            ],
                            'enableClientValidation' => false
                        ]
                );
                echo '<ul>';
                echo $form->field($model,'name')->textInput(['class'=>'txt']);



//            $url=\yii\helpers\Url::toRoute(['get-region']);
            echo $form->field($model, 'region')->widget(\chenkby\region\Region::className(),[
                'model'=>$model,
                'url'=>\yii\helpers\Url::toRoute(['get-region']),
                'province'=>[
                    'attribute'=>'province_id',
                    'items'=>\frontend\models\Locations::getRegion(),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'请选择']
                ],
                'city'=>[
                    'attribute'=>'city_id',
                    'items'=>\frontend\models\Locations::getRegion($model['province_id']),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'请选择']
                ],
                'district'=>[
                    'attribute'=>'area_id',
                    'items'=>\frontend\models\Locations::getRegion($model['city_id']),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'请选择']
                ]
            ]);


                echo $form->field($model,'address')->textInput(['class'=>'txt']);
                echo $form->field($model,'tel')->textInput(['class'=>'txt']);
                echo $form->field($model,'status',['template'=>"{label}\n{input}设为默认地址",])->checkbox(['class'=>'check'],false)->label('&nbsp;');
                echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" name="" class="btn" value="保存">
                    </li>';
                echo '</ul>';
                \yii\widgets\ActiveForm::end();
            ?>
        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->
