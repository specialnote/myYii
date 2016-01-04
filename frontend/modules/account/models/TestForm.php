<?php
    namespace frontend\modules\account\models;

    use yii\base\Model;
    use Yii;

    class TestForm extends Model{
        public $email;
        public $username;
        public $mobile;
        public $pass;
        public $confirm;

        public function rules(){
            return [
                ['email','email'],
                ['username','match','pattern'=>Yii::$app->params['form.username']],
                ['mobile','match','pattern'=>Yii::$app->params['form.mobile']],
                [['pass','confirm'],'match','pattern'=>Yii::$app->params['form.password']],
                ['confirm','compare','compareAttribute'=>'pass','operator'=>'==='],
                ['mobile','string','max'=>11,'min'=>11],
                [['pass','confirm'],'string','min'=>6],
                [['email','mobile','username','pass','confirm'],'required','on'=>['register']],
                [['username','pass'],'required','on'=>['login']],
            ];
        }

        public function attributeLabels(){
            return [
                'email'=>'邮箱',
                'nickname'=>'昵称',
                'mobile'=>'手机',
                'pass'=>'密码',
                'confirm'=>'确认密码'
            ];
        }

        public function scenarios(){
            return [
                'login'=>['username','pass'],
                'register'=>['username','mobile','email','pass','confirm'],
            ];
        }
    }
?>