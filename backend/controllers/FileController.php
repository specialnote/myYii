<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/17 0017
 * Time: 下午 4:08
 */
namespace backend\controllers;

use common\models\FileUpload;
use yii\web\Response;
use Yii;


class FileController extends BaseController
{
    public function actionUpload(){
        Yii::$app->response->format=Response::FORMAT_JSON;
        $model = New FileUpload();
        $model->fileInputName = 'file';
        if($model->save()){
            return ['code'=>0,'message'=>$model->baseName . '.' . $model->extension.' 上传成功('.intval($model->fileSize/1000).'KB)!','path'=>$model->urlPath];
        }else{
            return ['code'=>1,'message'=>$model->baseName . '.' . $model->extension.' 上传失败!'];
        }
    }

}