<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;//密码明文
    public $secondPassword;//第二次输入密码
    public $catpCode;//验证码
    public $smsCode;//短信验证码
    /**
     * @inheritdoc
     */
    //api注册
    const SCENARIO_API_REGISTER='api_register';
    const SCENARIO_WEB_REGISTER='regist_register';
//    const SCENARIO_WEB_SMS='regist_sms';
//    const SCENARIO_API_SMS='api_sms';

    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password','username','tel','email','smsCode'],'required'],//,'smsCode'  为了测试接口
            [['last_login_time', 'last_login_ip', 'status', 'create_at', 'update_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['auth_key'], 'unique'],
            [['username'], 'unique','message'=>'用户名已存在'],
            ['email','email','skipOnEmpty'=>false],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'match','pattern'=>'/^1[34578]\d{9}$/','skipOnEmpty'=>false],
            ['catpCode','captcha','on'=>self::SCENARIO_WEB_REGISTER,'captchaAction'=>'regist/captcha'],
            ['catpCode','captcha','on'=>self::SCENARIO_API_REGISTER,'captchaAction'=>'api/captcha'],
            ['catpCode','string'],
            ['smsCode','smsCode'],


            ['secondPassword','pwdTopwd','skipOnEmpty'=>false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名 : ',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码密文',
            'password' => '密码 : ',
            'secondPassword' => '确认密码:',
            'email' => '邮箱 : ',
            'tel' => '手机号 : ',
            'catpCode' => '验证码 : ',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'status' => '状态',
            'create_at' => '注册时间',
            'update_at' => '修改时间',
            'smsCode' => '短信验证:',
        ];
    }
    public function pwdTopwd(){
        if($this->password!=$this->secondPassword){
            $this->addError('secondPassword','两次密码不一致');
        }
    }
    //验证短信验证码
    public function smsCode(){
        //获取缓存的短信和电话号码对信息
        $value=Yii::$app->cache->get('tel_'.$this->tel);
        if (!$value || $this->smsCode!=$value){
            $this->addError('smsCode','验证码不正确');
        }
    }
    //设置保存之前要做的事
    public function beforeSave($insert)
    {
        if ($insert){
            $this->create_at=time();
            $this->status=1;
            $this->auth_key=\Yii::$app->security->generateRandomString();
            $this->last_login_time=time();
            $this->last_login_ip=ip2long(\Yii::$app->request->userIP);

        }else{
            $this->update_at=time();
        }
        if ($this->password){
            $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
       return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey()==$authKey;
    }
}