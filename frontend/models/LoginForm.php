<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;//验证码


    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            //验证用户名
            ['username','validateUsername','params'=>Yii::$app->params['regex.username']],
            // password is validated by validatePassword()
            ['password', 'validatePassword','params'=>Yii::$app->params['regex.password']],
            //验证码
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户',
            'password' => '密码',
            'verifyCode'=>'验证码',
            'rememberMe'=>'记住我',
        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if(!$this->password){
            $this->addError($attribute, '密码不能为空。');
        }
        if (!$this->hasErrors()) {
            if(preg_match($params,$this->password)){
                $user = $this->getUser();
                if (!$user->validatePassword($this->password)) {
                    $this->addError($attribute, '密码不正确。');
                }
            }else{
                $this->addError($attribute,'密码不合法。');
            }

        }
    }
    /*
     * 验证用户名
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     * */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if(preg_match($params,$this->username)){
                $user = $this->getUser();
                if (!$user) {
                    $this->addError($attribute, '用户名不存在。');
                }
            }else{
                $this->addError($attribute, '用户名不合法。');
            }

        }
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 7 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        if($this->_user === null){
            $this->_user = User::findByEmail($this->username);
        }
        if($this->_user === null){
            $this->_user = User::findByMobile($this->username);
        }
        return $this->_user;
    }
}
