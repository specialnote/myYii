<?php
namespace backend\controllers;

use backend\models\ResetPasswordForm;
use Yii;
use backend\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' =>  [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 50,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 4
            ],
        ];
    }
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) return $this->redirect(['/site/login']);
        return $this->render('index');
    }

    /**
     * 登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $this->layout = false;
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(!Yii::$app->user->identity->changed_password){
                return $this->redirect(['/site/password']);
            }
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * 修改面膜
     * @return string|\yii\web\Response
     */
    public function actionPassword(){
        if(Yii::$app->user->isGuest) return $this->redirect(['/site/login']);
        $model = new ResetPasswordForm(Yii::$app->user->identity->password_reset_token);
        if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
            Yii::$app->user->logout();
            return $this->redirect(['/site/login']);
        }
        return $this->render('password',[
            'model'=>$model
        ]);
    }
}
