<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '角色权限';
$this->params['breadcrumbs'][] = ['label' => '管理员', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$action=Yii::$app->controller->action->id;
?>
<div class="role-node">
    <?=Html::beginForm() ?>
    <?php foreach($roles as $role): ?>
        <?= Html::checkbox('roles[]',in_array($role->name,$roleNames),['value'=>$role->name,'id'=>$role->name]) ?>
        <?= Html::label($role->description,$role->name) ?>
    <?php endforeach; ?>
    <p><?= Html::submitButton('保存',['class'=>'btn btn-primary']) ?></p>
    <?= Html::endForm() ?>
</div>
