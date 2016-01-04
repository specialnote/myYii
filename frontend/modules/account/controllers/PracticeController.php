<?php
    namespace frontend\modules\account\controllers;

    use frontend\modules\account\models\TestForm;
    use yii\web\Controller;

    class PracticeController extends Controller{
        public function actionIndex(){
            $model = new TestForm();
            $model->scenario = 'register';
            return $this->render('index',[
                'model'=>$model,
            ]);
        }
    }
?>