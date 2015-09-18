<?php
namespace common\widgets;

use backend\assets\UEditorAsset;
use yii;
use yii\helpers\Html;
use yii\base\Exception;

class UEditor extends yii\bootstrap\Widget{

    public $model;
    public $attribute;
    public $name;

    private $_html;


    public function init(){
        parent::init();
        if(!$this->attribute){
            throw new Exception('attribute属性必须存在');
        }
        if(!$this->name){
            throw new Exception('name属性不能为空');
        }
        $this->attribute = Html::encode($this->attribute);
        $this->name = Html::encode($this->name);
    }
    public function run(){

        if($this->model){
            $model = $this->model;
            $attribute = $this->attribute;
            $content = $model->$attribute;
        }else{
            $content = '';
        }

        $this->_html.='<script id="'.$this->attribute.'"  name='.$this->name.'"  type="text/plain">'.$content.'</script>';

        UEditorAsset::register($this->view);
        $this->view->registerJs('
             var ue = UE.getEditor("'.$this->attribute.'",{
                 autoHeightEnabled: false,

             });
        ');
        return $this->_html;
    }

}