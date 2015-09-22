<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password
 * @property string $password_reset_token
 * @property string $email
 * @property integer $changed_password
 * @property string $last_login_time
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $group
 * @property integer $mobile
 */
class User extends \yii\db\ActiveRecord  implements IdentityInterface
{
    public $verifyCode;//验证码
    public $pass1; //新密码
    public $pass2; //确认密码

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const GROUP_READER = 10;
    const GROUP_WRITER = 20;
    const GROUP_ADMIN  = 30;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'mobile', 'email', 'created_at', 'updated_at'], 'required'],
            [['changed_password', 'status', 'created_at', 'updated_at', 'group'], 'integer'],
            [['last_login_time'], 'safe'],
            [['password_reset_token'], 'unique'],
            [['auth_key', 'email'], 'string', 'max' => 32],
            [['username', 'password', 'password_reset_token'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['mobile'], 'unique'],
            ['mobile','string','max'=>11,'min'=>11],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['group', 'default', 'value' => self::GROUP_READER],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['group', 'in', 'range' => [self::GROUP_READER, self::GROUP_WRITER,self::GROUP_ADMIN]],

            ['username', 'match', 'pattern' => Yii::$app->params['regex.username'],'message'=>'用户名不合法'],
            ['password','match','pattern'=>Yii::$app->params['regex.password'],'message'=>'密码不合法'],
            ['mobile','match','pattern'=>Yii::$app->params['regex.mobile'],'message'=>'手机号不合法'],
            ['email','match','pattern'=>Yii::$app->params['regex.email'],'message'=>'邮箱不合法'],

            ['verifyCode', 'captcha','message'=>'验证码不正确','captchaAction'=>'/site/captcha','on'=>['login']],
            ['verifyCode', 'captcha','message'=>'验证码不正确','captchaAction'=>'/user/captcha','on'=>['change_username','change_password','change_email','change_mobile']],
            ['pass1','match','pattern'=>Yii::$app->params['regex.password'],'message'=>'新密码不合法','on'=>['change_password']],
            ['pass2','match','pattern'=>Yii::$app->params['regex.password'],'message'=>'确认密码不合法','on'=>['change_password']],
            ['pass2','compare','compareAttribute'=>'pass1','operator'=>'===','message'=>'确认密码和新密码不一样','on'=>['change_password']],

            ['password','filter','filter'=>function($value){
                return Yii::$app->security->generatePasswordHash($value);
            },'on'=>'create_user'],
            ['changed_password','filter','filter'=>function($value){
                return false;
            },'on'=>'create_user'],
        ];
    }

    /*
     *自定义场景
     * */
    public function scenarios()
    {
        return [
            'login' => ['username', 'password'],
            'register' => ['username', 'email', 'password','mobile'],
            'change_username'=>['username','verifyCode'],
            'change_password'=>['password','pass1','pass2','verifyCode'],
            'create_user'=>['username','password','email','mobile','face','status','group','changed_password'],
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
            'password' => '密 码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'changed_password' => 'Changed Password',
            'last_login_time' => '最后登录时间',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'group' => '分组',
            'mobile' => '手机',

            'verifyCode'=>'验证码',
            'pass1'=>'新密码',
            'pass2'=>'确认密码',
        ];
    }

    /**
     * @param bool $insert
     */
   /* public function beforeSave($insert){
           if(parent::beforeSave($insert)){
               if($insert == 'insert'){
                   $this->password = Yii::$app->security->generatePasswordHash($this->password);
                   $this->changed_password = 0;
                   return true;
               }
           }
        return false;
    }*/

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotFoundHttpException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /*
     * find user by email
     * @param string $email
     * @return static|null
     * */
    public static function findByEmail($email){
        return static::findOne(['email'=>$email,'status'=>self::STATUS_ACTIVE]);
    }
    /*
     * find user by mobile
     * @param string $mobile
     * @return static|null
     * */
    public static function findByMobile($mobile){
        return static::findOne(['email'=>$mobile,'status'=>self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getIsAdmin(){
        return $this->group === User::GROUP_ADMIN;
    }

    /*
     * 设置不同场景
     * */
    public function setMyScenario($act){
        switch($act){
            case 'username':
                $this->setScenario('change_username');
                break;
            case 'mobile':
                break;
            case 'email':
                break;
            case 'password':
                $this->setScenario('change_password');
                break;
            case 'face':
                break;
            default:
                break;
        }
    }

    /*
     * 获取状态名称
     * */
    public function getStatusName(){
        if($this->status === User::STATUS_ACTIVE){
            return '可用';
        }else{
            return '禁用';
        }
    }

    /*
     * 获取分组名称
     * */
    public function getGroupName(){
        if($this->group === User::GROUP_ADMIN){
            return '管理员';
        }elseif($this->group === User::GROUP_READER){
            return '普通用户';
        }elseif($this->group === User::GROUP_WRITER){
            return '作者';
        }else{
            return '未知';
        }
    }
    
}
