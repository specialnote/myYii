<?php
    namespace frontend\controllers;

    use yii\web\Controller;

    class MyArticleController extends Controller{
        public function actions()
        {
            return [
                // 用配置数组申明 "view" 操作
                'special' => [
                    'class' => 'yii\web\ViewAction',
                    'viewPrefix' => 'special',
                    'viewParam'=>'aa',
                ],
            ];
        }

        public function actionIndex(){

        }
    }
?>