<?php
    namespace common\models;

    use yii\base\Exception;
    use yii\base\Object;
    use Yii;
    use yii\bootstrap\Html;

    class Mail extends Object{
        public $toUser;
        public $subject;
        public $content;

        public function init(){
            if($this->toUser || $this->subject || $this->content){
                throw new Exception('邮件属性不能为空');
            }
            $this->subject = Html::encode($this->subject);
        }

        public function send(){
            $mail= Yii::$app->mailer->compose();
            $mail->setTo($this->toUser);
            $mail->setSubject($this->subject);
            $mail->setHtmlBody($this->content);    //发布可以带html标签的文本
            return $mail->send();
        }
    }
?>