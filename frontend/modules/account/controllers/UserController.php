<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/4 0004
 * Time: 下午 3:24
 */

namespace frontend\modules\account\controllers;

use frontend\modules\account\Module;
use yii\web\Controller;

class UserController extends Controller{
    public function actionIndex(){
        $model = Module::getInstance();
        return $this->render('index',[
            'model'=>$model,
        ]);
    }
}