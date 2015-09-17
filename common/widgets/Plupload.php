<?php
namespace common\widgets;

use backend\assets\UEditorAsset;
use backend\assets\UploadAsset;
use yii;
use yii\helpers\Html;
use yii\base\Exception;

class Plupload extends yii\bootstrap\Widget{

    public $model;
    public $attribute;
    public $name;
	public $url;

    private $_html;


    public function init(){
        parent::init();
		if(!$this->url){
			throw new Exception('url参数不能为空');
		}
    }
    public function run(){
        $this->_html .= '<div id="container"><a id="pickfiles" href="javascript:;">[Select files]</a><a id="uploadfiles" href="javascript:;">[Upload files]</a><p></p></div>';
        $this->_html .= '<pre id="console"></pre>';

		UploadAsset::register($this->view);

		$this->view->registerJs(
			'$(function(){
                initCoverImageUploader("pickfiles","container","2mb","'.$this->url.'","'.Yii::$app->request->getCsrfToken().'");
            });'
		);

        return $this->_html;
    }

}