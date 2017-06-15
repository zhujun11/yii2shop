<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_time
 * @property string $last_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $topassword;
    public $password;
    static public $allStatus=[0=>'禁用',1=>'正常'];
    /**
     * @inheritdoc
     */
    const SCENARIO_ADD='add';//定义场景

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password','required','on'=>self::SCENARIO_ADD],//使用场景
            [['username', 'email',], 'required'],
            [['status', 'created_at', 'updated_at', 'last_time'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            ['password', 'string','length'=>[6,32],'tooShort'=>'密码不少于6位','tooLong'=>'密码是超过32位'],
            [['last_ip'], 'string', 'max' => 30],
            [['email'], 'email'],
            [['email'],'unique','message'=>'邮箱已被占用'],
            [['username'], 'unique','message'=>'用户名已被占用'],
            [['password_reset_token'], 'unique'],
            ['topassword','valitopassword','skipOnEmpty'=>false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => 'password_hash',
            'password' => '密码',
            'topassword'=>'确认密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_time' => 'Last Time',
            'last_ip' => 'Last Ip',
        ];
    }
    public function valitopassword(){
        if($this->password!=$this->topassword){
            $this->addError('topassword','两次密码不一致');
        }
    }
    public function beforeSave($insert){
        if ($insert){//是插入数据(添加)的情况才有创建时间
            $this->created_at=time();
            $this->status=1;
            //设置自动登录的token
            $this->auth_key=Yii::$app->security->generateRandomString();
        }
        if($this->password){//有明文密码才加密

        $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
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
