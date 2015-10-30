<?php
namespace backend\models;

use common\models\User;
use yii\base\InvalidParamException;
use Yii;
use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $pass1;
    public $pass2;
    public $verifyCode;

    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param  string  $token
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password','pass1','pass2','verifyCode'], 'required'],
            [['password','pass1','pass2'], 'string', 'min' => 6],
            ['verifyCode', 'captcha','message'=>'验证码不正确'],
            ['pass2','compare','compareAttribute'=>'pass1','operator'=>'===','message'=>'确认密码和新密码不一样'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => '原密码',
            'pass1' => '新密码',
            'pass2'=>'确认密码',
            'verifyCode'=>'验证码',

        ];
    }
    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        if(!Yii::$app->security->validatePassword($this->password,$user->password))return false;
        $user->setPassword($this->pass1);
        $user->removePasswordResetToken();
        $user->generatePasswordResetToken();
        $user->changed_password = true;
        return $user->save(false);
    }
}
