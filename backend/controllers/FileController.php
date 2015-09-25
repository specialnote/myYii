<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/17 0017
 * Time: 下午 4:08
 */
namespace backend\controllers;

use common\models\ImageUpload;
use yii\web\Response;
use Yii;


class FileController extends BaseController
{
    public function actionUpload(){
        Yii::$app->response->format=Response::FORMAT_JSON;
        $model = New ImageUpload();
        $model->fileInputName = 'file';
        if($model->save()){
            return ['code'=>0,'message'=>$model->getMessage(),'path'=>$model->getUrlPath()];
        }else{
            return ['code'=>1,'message'=>$model->getMessage()];
        }
    }

}