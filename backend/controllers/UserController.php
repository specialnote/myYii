<?php
namespace backend\controllers;

use Yii;
use yii\base\Exception;

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

        $model->setMyScenario($act);//设置场景
        $change = Yii::$app->request->post('change');
        if($change){
            switch($change) {
                case 'username':
                    if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        return $this->goBack();
                    } else {
                        throw new Exception('用户名修改失败');
                    }
                    break;
                case 'password':
                    $form = Yii::$app->request->post('User');
                    if(!$model->validate('verifyCode')){
                        echo '验证码不正确';
                        die;
                    }
                    if (Yii::$app->security->validatePassword($form['password'], $model->password)) {
                        if ($form['pass1'] === $form['pass2']) {
                            $model->password = Yii::$app->security->generatePasswordHash($form['pass1']);
                            if ($model->save()) {
                                return $this->goBack();
                            } else {
                                echo '<pre>';
                                var_dump($_POST);
                                //throw new Exception('密码修改失败');
                            }
                        } else {
                            throw new Exception('确认密码和新密码不一样');
                        }
                    } else {
                        throw new Exception('原密码不正确');
                    }
                    break;
                default:
                    break;
            }
        }else{
            return $this->render('change',[
                'act'=>$act,
                'model'=>$model,
            ]);
        }

    }
}
