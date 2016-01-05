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
                'username'=>'昵称',
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


        // 明确列出每个字段，特别用于你想确保数据表或模型属性改变不会导致你的字段改变(保证后端的API兼容).
        /*public function fields()
        {
            return [
                'email',
                'username',
                'mobile'
            ];
        }*/

        // 过滤掉一些字段，特别用于你想继承父类实现并不想用一些敏感字段
        //fields只能对toArray()形式的数据导出进行过滤，不能对->attributes 的形式进行过滤
       public function fields()
        {
            $fields = parent::fields();
            unset($fields['pass'], $fields['confirm']);

            return $fields;
        }
    }
?>