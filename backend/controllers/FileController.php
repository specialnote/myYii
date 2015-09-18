<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/17 0017
 * Time: 下午 4:08
 */
namespace backend\controllers;

use common\models\FileUpload;
use yii\web\UploadedFile;


class FileController extends BaseController
{
    public function actionUpload(){
        $model = New FileUpload();
        $model->fileInputName = 'file';
        if($model->save()){
            die(json_encode(['code'=>'200','message'=>$model->baseName . '.' . $model->extension.' 上传成功('.intval($model->fileSize/1000).'KB)!','path'=>$model->urlPath]));
        }else{
            die(json_encode(['code'=>'200','message'=>$model->baseName . '.' . $model->extension.' 上传失败!']));
        }
    }

}