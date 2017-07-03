<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
?>
<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>

    <div class="login_bd">
        <div class="login_form fl">
            <?php
            $form=\yii\widgets\ActiveForm::begin(
                [
                    'fieldConfig'=>[
                        'options'=>[
                            'tag'=>'li',
                        ],
                        'errorOptions'=>[
                            'tag'=>'p' ,

                        ]
                    ]
                ]
            );
            echo '<ul>';
            echo $form->field($model,'username')->textInput(['class'=>'txt']);
            echo $form->field($model,'password')->passwordInput(['class'=>'txt']);
            echo $form->field($model,'secondPassword')->passwordInput(['class'=>'txt']);
            echo $form->field($model,'email')->textInput(['class'=>'txt']);
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);

            $button='<input type="button" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>';

            echo $form->field($model,'smsCode',['template'=>"{label}\n{input} $button\n{error}"])->textInput(['class'=>'txt','placeholder'=>"输入短信验证码",'disabled'=>"disabled",'id' =>"captcha"]);
            echo $form->field($model,'catpCode',['options'=>['class'=>'checkcode']])->widget(yii\captcha\Captcha::className(),[
                    'template'=>'{input}{image}',
                'options'=>['class'=>'txt'],

            ]);
            echo '<li>
                <label for="">&nbsp;</label>
                <input type="submit" value="" class="login_btn" />
             </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>

                    <!--<li>
                        <label for="">验证码：</label>
                        <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="captcha" disabled="disabled" id="captcha"/> <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>

                    </li>-->



        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->
<?php
/**
 * @var $this \yii\web\View
 */
$url=\yii\helpers\Url::to(['regist/sms']);
$js=new \yii\web\JsExpression(
        <<<JS
        $('#get_captcha').click(function bindPhoneNum(){
			        
			var tel=$('#member-tel').val();
			// console.debug(tel);
			//AJAX post提交tel参数到 user/send-sms
			$.post('$url',{tel:tel},function(data) {
			    if (data=='success'){
			        //启用输入框
                    $('#captcha').prop('disabled',false);
        
                    var time=120;
                    var interval = setInterval(function(){
                        time--;
                        if(time<=0){
                            clearInterval(interval);
                            var html = '获取验证码';
                            $('#get_captcha').prop('disabled',false);
                        } else{
                            var html = time + ' 秒后再次获取';
                            $('#get_captcha').prop('disabled',true);
                        }
                        
                        $('#get_captcha').val(html);
                    },1000);
			        alert('发送成功,请及时查看')
			    }else {
			        alert(data)
			    }
			})
		})
        

JS

);
$this->registerJs($js);
//$this->registerJsFile('@web/js/jquery-1.8.3.min.js')
?>