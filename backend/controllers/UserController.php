<?php
namespace backend\controllers;

use Yii;
use common\models\LoginForm;

/**
 * Site controller
 */
class UserController extends BaseController
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
        return $this->render('index');
    }

    public function actionChange(){
        $act = Yii::$app->request->get('act');
        $act = $act?$act:'username';
        $model = Yii::$app->user->identity;
        switch($act){
            case 'username':
                $model->setScenario('change_username');
                break;
            case 'mobile':
                break;
            case 'email':
                break;
            case 'password':
                break;
            case 'face':
                break;
            default:
                break;
        }
        if($model->load(Yii::$app->request->post()) && $model->save()){
            return $this->goBack();
        }else{
            return $this->render('change',[
                'act'=>$act,
                'model'=>$model,
            ]);
        }

    }
}
