<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/30 0030
 * Time: 上午 10:49
 */
namespace frontend\controllers;

use common\models\Mail;
use common\models\User;
use yii\base\Exception;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

class UserController extends Controller{
    /**
     * 个人中心
     * @return string
     */
    public function actionIndex(){
        $model = Yii::$app->user->identity;
        return $this->render('index',[
            'model'=>$model,
        ]);
    }

    /**
     * 验证邮箱,发送邮件
     * @throws Exception
     */
    public function actionCategoryEmail(){
        $model = Yii::$app->user->identity;
        if(!$model->email) throw new Exception('邮箱不能为空');
        $mail = new Mail();
        $mail->toUser = $model->email;
        $mail->subject = 'MyYii 邮件验证';
        $mail->content = '
            <h2>MyYii 邮件验证</h2>
            <p>
                <a target="_blank" href="'.Yii::$app->request->hostInfo.Url::to(['/user/email','a'=>$model->auth_key]).'">您正在验证您的邮箱('.$model->email.')，请点击此处进行验证'.Yii::$app->request->hostInfo.Url::to(['/user/email','a'=>$model->auth_key]).'</a>
            </p>
';
        if($mail->send()){
            echo '发送成功';
        }else{
            echo '发送失败';
        }
    }

    /**
     * 验证邮箱
     * @return \yii\web\Response
     * @throws Exception
     */
    public function actionEmail(){
        $a = Html::encode(Yii::$app->request->get('a'));
        if(!$a) throw new Exception('链接错误');
        $model = Yii::$app->user->identity;
        if($model->validateAuthKey($a)){
            $model->generateAuthKey();
            $model->is_email = User::IS_EMAIL_TRUE;
            if($model->save(false)){
                return $this->redirect(['/user/index']);
            }else{
                throw new Exception('验证邮箱失败');
            }
        }else{
            throw new Exception('身份不正确');
        }
    }


}