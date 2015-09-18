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
        if(!$this->model){
            throw new Exception('model属性不能为空');
        }
        if(!$this->attribute){
            throw new Exception('attribute属性不能为空');
        }
    }
    public function run(){
        $model = $this->model;
        $attribute = $this->attribute;
        $path = $model->$attribute?$model->$attribute:"/images/noimage.gif";
        $this->_html.='<div class="form-group field-article-author" id="container">';
        $this->_html.=Html::activeLabel($model,$attribute);
        $this->_html.=Html::activeHiddenInput($model,$attribute,['id'=>'hidden_input']);
        $this->_html .= '<div id="pickfiles" style="height:150px;min-width:150px;max-width: 300px;overflow: hidden;"><img height="150" src="'.$path.'" /></div>';
        $this->_html.='</div>  ';
		UploadAsset::register($this->view);

		$this->view->registerJs(
			'$(function(){
                initCoverImageUploader("pickfiles","container","2mb","'.$this->url.'","'.Yii::$app->request->getCsrfToken().'");
            });'
		);

        return $this->_html;
    }

}