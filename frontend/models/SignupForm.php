<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirm;
    public $mobile;
    public $group;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 20],
            ['username', 'match', 'pattern' => Yii::$app->params['regex.username'],'message'=>'用户名不合法'],

            ['mobile', 'filter', 'filter' => 'trim'],
            ['mobile', 'required'],
            ['mobile', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This mobile has already been taken.'],
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            ['mobile','match','pattern'=>Yii::$app->params['regex.mobile'],'message'=>'手机不合法'],


            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 30],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['email','match','pattern'=>Yii::$app->params['regex.email'],'message'=>'邮箱不合法'],

            ['password', 'required'],
            ['confirm', 'required'],
            ['password', 'string', 'min' => 6],
            ['confirm', 'string', 'min' => 6],
            ['password','match','pattern'=>Yii::$app->params['regex.password'],'message'=>'密码不合法'],
            ['confirm','match','pattern'=>Yii::$app->params['regex.password'],'message'=>'确认密码不合法'],
            ['confirm','compare','compareAttribute'=>'password','operator'=>'===','message'=>'确认密码和新密码不一样'],

            ['group','required'],
            ['group','integer','message'=>'group不合法'],
            ['group', 'in', 'range' => [User::GROUP_READER, User::GROUP_WRITER,User::GROUP_ADMIN],'message'=>'用户类型不合法'],
        ];
    }

    public function attributeLabels(){
        return[
            'username'=>'用户名',
            'email'=>'邮箱',
            'mobile'=>'电话',
            'password'=>'密码',
            'confirm'=>'确认密码',
            'group'=>'用户类型',
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signUp()
    {
        if ($this->validate(['username','password','confirm','email','mobile','group'])) {
            if($this->password !== $this->confirm){
                return null;
            }
            $user = new User();
            $user->setScenario('register');
            $user->username = $this->username;
            $user->email = $this->email;
            $user->mobile = $this->mobile;
            $user->status = User::STATUS_ACTIVE;
            $user->group = $this->group;
            $user->changed_password = true;

            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
