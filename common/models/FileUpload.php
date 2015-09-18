<?php

namespace common\models;

use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class FileUpload extends \yii\base\Object
{
    public $fileInputName = 'file';
    public $savePath ;
    public $allowExt = ['jpg','png','jpeg','bmp','gif'];
    public $maxFileSize=1024*1000*50;
    public $extension;
    public $baseName;
    public $fileSize;
    public $urlPath;

    private $_uploadFile;
    private $_path;
    private $_name;
    private $filePath;



    public function init(){
        if(!$this->fileInputName){
            throw new Exception('fileInputName属性不能为空');
        }
        if(!$this->savePath){
            $this->savePath = \Yii::$app->basePath.'/web/uploads';
        }
        if(!file_exists(rtrim($this->savePath,'/'))){
            FileHelper::createDirectory($this->savePath);
        }

        $this->_uploadFile = UploadedFile::getInstanceByName($this->fileInputName);

        if(!$this->_uploadFile){
            throw new Exception('not has upload file');
        }

        if($this->_uploadFile->error){
            throw new Exception('upload failed');
        }

        $this->extension = $this->_uploadFile->extension;
        if(!in_array($this->extension,$this->allowExt)){
            throw new Exception('the extension of file is not allowed');
        }

        $this->fileSize = $this->_uploadFile->size;
        if($this->fileSize > $this->maxFileSize){
            throw new Exception('the file is out of maxFileSize');
        }

        if(!$this->_path){
            $path = date('Y-m',time());
            if(!file_exists($this->savePath.'/'.$path)){
                FileHelper::createDirectory($this->savePath.'/'.$path);
            }
            $this->_path = $path;
        }

        $this->baseName = $this->_uploadFile->baseName;
        $this->_name = \Yii::$app->security->generateRandomString();
        $this->filePath = $this->savePath.'/'.$this->_path.'/'.$this->baseName.$this->_name.'.'.$this->extension;
        $this->urlPath = '/uploads/'.$this->_path.'/'.$this->baseName.$this->_name.'.'.$this->extension;
    }

    public function save(){
        if($this->_uploadFile->saveAs($this->filePath)){
            return true;
        }else{
            return false;
        }
    }


}
