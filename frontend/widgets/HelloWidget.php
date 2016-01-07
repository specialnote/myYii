<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/6 0006
 * Time: 上午 10:08
 */

namespace frontend\widgets;


use yii\helpers\Html;

class HelloWidget extends \yii\bootstrap\Widget
{
    public $content;

    public function init(){
        parent::init();
        if(!$this->content){
            $this->content = 'Hello Widgets';
        }
    }

    public function run(){
        //return Html::encode($this->content);
        return $this->render('hello',['content'=>$this->content]);
    }
}