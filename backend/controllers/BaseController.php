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

            $node = $controllerId.'_'.$actionId;
            //未登录
            if(Yii::$app->user->isGuest){
                if(!in_array($node,['site_login','site_register','site_index','site_captcha'])){
                    throw new ForbiddenHttpException('未登录用户无权访问');
                }else{
                    return true;
                }
            }else{
                //都可以访问
                if(in_array($node,['site_login','site_register','site_index','site_captcha','site_logout'])){
                    return true;
                }
                //非作者、管理员不能登录后台
                if(!in_array(Yii::$app->user->identity->group,[User::GROUP_ADMIN,User::GROUP_WRITER])){
                    Yii::$app->user->logout();
                    throw new ForbiddenHttpException('普通用户无权访问');
                }
                
                //为改密码(待修改)
                if(!Yii::$app->user->identity->changed_password){
                    throw new Exception('请更改密码');
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
