<?php
    namespace frontend\modules\account\controllers;

    use frontend\modules\account\models\TestForm;
    use yii\web\Controller;
    use Yii;

    class PracticeController extends Controller{
        //表单练习
        public function actionIndex(){
            $model = new TestForm();
            $model->scenario = 'register';
            if(Yii::$app->request->isPost){
                $model->load(Yii::$app->request->post());
                echo '<pre>';
                var_dump($model->toArray());
                var_dump($model->attributes);
                echo '</pre>';
            }else{
                return $this->render('index',[
                    'model'=>$model,
                ]);
            }
        }

        public function actionWidget(){
            return $this->render('widget');
        }
    }
?>