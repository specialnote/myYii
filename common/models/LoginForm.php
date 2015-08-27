<?php
namespace common\models;

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
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            //自定义用户名验证方法
            ['username','validateUsername'],
            //用正则验证用户名和密码
            ['username', 'match', 'pattern' => Yii::$app->params['regex.username'],'message'=>'用户名不合法'],
            ['password','match','pattern'=>Yii::$app->params['regex.password'],'message'=>'密码不合法'],

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
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect password.');
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
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'Incorrect username.');
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
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
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
