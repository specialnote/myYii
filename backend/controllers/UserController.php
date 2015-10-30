<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

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
            $model->generatePasswordResetToken();
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->status = User::STATUS_ACTIVE;
        $model->group = User::GROUP_READER;
        return $this->render('create', [
            'model' => $model,
        ]);
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
            $model->generatePasswordResetToken();
            $model->save(false);
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

    #为用户选择角色
    public function actionRole($id){
        $user = User::findOne($id);
        if(!$user) throw new NotFoundHttpException('用户未找到');
        $authManager = Yii::$app->authManager;
        if(Yii::$app->request->isPost){
            $roleNames=Yii::$app->request->post('roles');
            $authManager->revokeAll($id);
            if(!empty($roleNames)&&is_array($roleNames)){
                foreach($roleNames as $roleName){
                    $role=$authManager->getRole($roleName);
                    if(!$role){
                        continue;
                    }
                    $authManager->assign($role,$id);
                }
            }
            Yii::$app->session->setFlash('success','更新成功');
            $this->redirect(['role','id'=>$id]);
        }else{
            $userRoles=$authManager->getRolesByUser($id);
            $roleNames=ArrayHelper::getColumn(ArrayHelper::toArray($userRoles),'name');
            $roles=$authManager->getRoles();
            return $this->render('role',['roles'=>$roles,'roleNames'=>$roleNames]);
        }
    }

}
