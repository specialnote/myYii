<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Html;

class NodeForm extends Model
{
    public $name;
    public $description;
    public $parent;

    public function rules(){
        return [
            [['name','parent'],'string','max'=>20],
            [['name','description'],'required','message'=>'name属性不能为空'],
            ['name','match','pattern'=>'/^[a-z][a-z-_]{2,20}$/','message'=>'name属性不合法'],
            ['parent','match','pattern'=>'/^[a-z-_][a-z-_]{2,20}$/','message'=>'parent属性不合法'],
            ['parent','validateParent'],
            ['description','filter','filter'=>function($value){
                return Html::encode($value);
            }],
        ];
    }

    public function validateParent($attribute,$params){
        if(!$this->hasErrors()){
            $authManager = Yii::$app->authManager;
            $node = $authManager->getPermission($this->parent);
            if(empty($node)){
                $this->addError($attribute,'上级节点不存在');
            }
        }
    }

    public function attributeLabels(){
        return [
            'name'=>'节点名称',
            'description'=>'节点描述',
            'parent'=>'父级节点',
        ];
    }

    public function save(){
        if($this->validate()){
            $authManager = Yii::$app->authManager;
            $node = $authManager->createPermission($this->name);
            $node->description = $this->description;
            $authManager->add($node);

            if(!empty($this->parent)){
                $parent = $authManager->getPermission($this->parent);
                $authManager->addChild($parent,$node);
            }
            return true;
        }else{
            return false;
        }
    }

    public function update($name){
        if($this->validate()){
            $authManager = Yii::$app->authManager;
            $node = $authManager->getPermission($name);
            if(!$node) return false;
            $authManager->remove($node);

            $node = $authManager->createPermission($this->name);
            $node->description = $this->description;
            $authManager->add($node);
            if(!empty($this->parent)){
                $parent = $authManager->getPermission($this->parent);
                $authManager->addChild($parent,$node);
            }
            return true;
        }
        return false;
    }

  
}
