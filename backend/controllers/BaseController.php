<?php
namespace backend\controllers;

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
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','captcha'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action){
        if(parent::beforeAction($action)){
            $controllerId = $action->controller->id;
            $actionId = $action->id;

            $node = $controllerId.'_'.$actionId;//拼装权限节点
            //未登录
            if(Yii::$app->user->isGuest){
                if(in_array($node,['site_login','site_index','site_captcha','site_error'])){
                    return true;
                }
            }else{
                //所有用户可以访问
                if(in_array($node,['site_login','site_password','site_logout','site_index','site_captcha','site_error'])){
                    return true;
                }
                //修改密码
                if(!Yii::$app->user->identity->changed_password && in_array($node,['site_login','site_index','site_captcha','site_logout','site_error','site_password'])){
                    return true;
                }
                //超级管理员(待修改)
                if(Yii::$app->user->id ==1){
                    return true;
                }
                //检查rbac权限
                if(Yii::$app->user->can($node)){
                    return true;
                }else{
                    throw new ForbiddenHttpException('无权访问');
                }
            }
        }else{
            return false;
        }
    }
}
