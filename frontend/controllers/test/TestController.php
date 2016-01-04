<?php
    namespace frontend\controllers\test;

    use yii\web\Controller;

    class TestController extends Controller{
        public function actionIndex(){
            echo '<h1>子目录控制器</h1>';
        }
    }
?>