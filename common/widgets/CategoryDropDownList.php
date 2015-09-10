<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/10 0010
 * Time: 下午 5:22
 */
namespace common\widgets;

use common\models\Category;
use yii;
use yii\helpers\Html;

class CategoryDropDownList extends yii\bootstrap\Widget{

    public $model;
    public $attribute;
    public $options=[];
    public $parent;
    private $_categories=[];
    private $_html;
    public function init(){
        parent::init();
        $this->options['encodeSpaces']=true;
        $this->options['prompt']='不选择';

        $categories=Category::getChildCategories(intval($this->parent));
        if(!empty($categories)){
            foreach($categories as $v){
                $tempArr=[];
                $tempArr[$v['id']]=str_repeat('&nbsp;&nbsp;',$v['class']-1).$v['name'];
                $this->_categories+=$tempArr;
            }
        }
        $this->_html='<div class="form-group">';
        $this->_html.=Html::activeLabel($this->model,$this->attribute);
        $this->_html.=Html::activeDropDownList($this->model,$this->attribute,$this->_categories,$this->options);
        $this->_html.='</div>';
    }
    public function run(){
        return $this->_html;
    }

}