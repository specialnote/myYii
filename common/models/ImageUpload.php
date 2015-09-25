<?php

namespace common\models;

use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class ImageUpload extends \yii\base\Object
{
    public $fileInputName = 'file';//上传表单 file name
    public $savePath ;//图像保存根位置
    public $allowExt = ['jpg','png','jpeg','gif','bmp'];//允许上传后缀
    public $maxFileSize=1024100000;//最大大小

    private $allowType = ['image/jpeg','image/bmp','image/gif','image/png','image/pjpeg','image/bmp','image/gif','image/x-png','image/pjpeg','image/bmp', 'image/gif' ,'image/x-png','image/pjpeg','image/bmp','image/gif','image/x-png'];
    private $_uploadFile;//上传文件
    private $_path;//生成路径
    private $_name;//生成名字
    private $filePath;//文件路径
    private $baseName;//上传文件去除后缀名字
    private $fileSize;//上传文件大小
    private $urlPath;//访问路径
    private $res=false;
    private $message;

    public function getMessage(){
        return $this->message;
    }
    public function getUrlPath(){
        return $this->urlPath;
    }

    public function init(){
        if(!$this->fileInputName) throw new Exception('fileInputName属性不能为空');

        if(!$this->savePath) $this->savePath = \Yii::$app->basePath.'/web/uploads/images';
        $this->savePath = rtrim($this->savePath,'/');
        if(!file_exists($this->savePath)){
            if(! FileHelper::createDirectory($this->savePath)){
                $this->message = '没有权限创建'.$this->savePath;
                return false;
            }
        }

        $this->_uploadFile = UploadedFile::getInstanceByName($this->fileInputName);
        if(!$this->_uploadFile){
            $this->message = '没有找到上传文件';
            return false;
        }
        if($this->_uploadFile->error){
            $this->message = '上传失败';
            return false;
        }

        file_put_contents('1111.txt',$this->_uploadFile->type);
        if(!in_array($this->_uploadFile->extension,$this->allowExt)){
            $this->message = '该文件类型不允许上传';
            return false;
        }
        if(!in_array($this->_uploadFile->type,$this->allowType)){
            $this->message = '该文件类型不允许上传';
            return false;
        }


        $this->fileSize = $this->_uploadFile->size;
        if($this->fileSize > $this->maxFileSize){
            $this->message = '文件过大';
            return false;
        }

        if(!$this->_path){
            $path = date('Y-m',time());
            if(!file_exists($this->savePath.'/'.$path)){
                FileHelper::createDirectory($this->savePath.'/'.$path);
            }
            $this->_path = $path;
        }

        $this->baseName = $this->_uploadFile->baseName;
        $this->_name = substr(\Yii::$app->security->generateRandomString(),-4,4);
        $this->filePath = $this->savePath.'/'.$this->_path.'/'.$this->baseName.'--'.$this->_name.'.'.$this->_uploadFile->extension;
        $this->urlPath = '/uploads/images/'.$this->_path.'/'.$this->baseName.'--'.$this->_name.'.'.$this->_uploadFile->extension;
    }

    public function save(){
        if($this->_uploadFile->saveAs($this->filePath)){
            $this->CreateThumbnail($this->filePath);
            $this->res = true;
        }else{
            $this->res = false;
        }
        if($this->res){
            $this->message = $this->baseName.'.'.$this->_uploadFile->extension.'上传成功';
        }else{
            $this->message = $this->baseName.'.'.$this->_uploadFile->extension.'上传失败';
        }
        return $this->res;
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
        //imagepng($ni, $toFile); //以 PNG 格式将图像输出到浏览器或文件

        switch ($data[2]) //1-GIF，2-JPG，3-PNG
        {
            case 1:
                imagegif($ni, $toFile);
                break;
            case 2:
                imagejpeg($ni, $toFile);
                break;
            case 3:
                imagepng($ni, $toFile);
                break;
        }
        ImageDestroy($ni);
        ImageDestroy($im);
        return $toFile;
    }
}
