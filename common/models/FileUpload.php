<?php

namespace common\models;

use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class FileUpload extends \yii\base\Object
{
    public $fileInputName = 'file';
    public $savePath ;
    public $allowExt = ['jpg','png','jpeg','gif'];
    public $maxFileSize=1024100000;
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
            $this->savePath = \Yii::$app->basePath.'/web/uploads/images';
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
        $this->filePath = $this->savePath.'/'.$this->_path.'/'.$this->baseName.'--'.$this->_name.'.'.$this->extension;
        $this->urlPath = '/uploads/images/'.$this->_path.'/'.$this->baseName.'--'.$this->_name.'.'.$this->extension;
    }

    public function save(){
        if($this->_uploadFile->saveAs($this->filePath)){
            $this->CreateThumbnail($this->filePath);
            return true;
        }else{
            return false;
        }
    }


    /**
     * 生成保持原图纵横比的缩略图，支持.png .jpg .gif
     * 缩略图类型统一为.png格式
     * $srcFile     原图像文件名称
     * $toW         缩略图宽
     * $toH         缩略图高
     * $toFile      缩略图文件名称，为空覆盖原图像文件
     * @return bool
     */
    public static function CreateThumbnail($srcFile, $toFile="", $toW=100, $toH=100)
    {
        if ($toFile == "") {
            $toFile = $srcFile;
        }
        $info = "";
        //返回含有4个单元的数组，0-宽，1-高，2-图像类型，3-宽高的文本描述。
        $data = getimagesize($srcFile, $info);
        if (!$data) return false;
        //将文件载入到资源变量im中
        switch ($data[2]) //1-GIF，2-JPG，3-PNG
        {
            case 1:
                //if(!function_exists("imagecreatefromgif")) return false;
                $im = imagecreatefromgif($srcFile);
                break;
            case 2:
               // if(!function_exists("imagecreatefromjpeg")) return false;
                $im = imagecreatefromjpeg($srcFile);
                break;
            case 3:
               // if(!function_exists("imagecreatefrompng")) return false;
                $im = imagecreatefrompng($srcFile);
                break;
        }

        //计算缩略图的宽高
        $srcW = imagesx($im);
        $srcH = imagesy($im);
        $toWH = $toW / $toH;
        $srcWH = $srcW / $srcH;
        if ($toWH <= $srcWH) {
            $ftoW = $toW;
            $ftoH = (int)($ftoW * ($srcH / $srcW));
        } else {
            $ftoH = $toH;
            $ftoW = (int)($ftoH * ($srcW / $srcH));
        }

        if (function_exists("imagecreatetruecolor")) {
            $ni = imagecreatetruecolor($ftoW, $ftoH); //新建一个真彩色图像
            if ($ni) {
                //重采样拷贝部分图像并调整大小 可保持较好的清晰度
                imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
            } else {
                //拷贝部分图像并调整大小
                $ni = imagecreate($ftoW, $ftoH);
                imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
            }
        } else {
            $ni = imagecreate($ftoW, $ftoH);
            imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
        }

        //保存到文件 统一为.png格式
        imagepng($ni, $toFile); //以 PNG 格式将图像输出到浏览器或文件
        ImageDestroy($ni);
        ImageDestroy($im);
        return true;
    }
}
