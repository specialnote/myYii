<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Exception;

/**
 * UserController implements the CRUD actions for User model.
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
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->setScenario('create_user');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->status = User::STATUS_ACTIVE;
            $model->group = User::GROUP_READER;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update_user');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return string
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionChange(){
        $act = Yii::$app->request->get('act');
        $act = $act?$act:'username';
        $model = $this->findModel(Yii::$app->user->id);

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
