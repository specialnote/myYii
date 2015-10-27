<?php
namespace console\controllers;

use yii\console\Controller;
use yii\web\NotFoundHttpException;

class ArticleController extends Controller{
    public function actionRun($name){
        $className = '\console\models\\'.ucfirst(strtolower($name)).'Spider';
        if(!class_exists($className)){
            throw new NotFoundHttpException($className.' Class not found');
        }

        $spider = new $className();
        $spider->process();
    }


}