<?php
namespace frontend\controllers;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * Base controller
 */
class BaseController extends Controller
{

    public function beforeAction($action){
        if(parent::beforeAction($action)){
            if(!in_array($action->controller->id,['article'])){
                return $this->redirect('/article/index');
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
}
