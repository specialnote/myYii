<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <div class="row">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-5">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true])->hint('用户名') ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true])->hint('邮箱') ?>
            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true,'autocomplete'=>'off'])->hint('电话') ?>
            <?= $form->field($model, 'pass1')->passwordInput(['autocomplete'=>'off','style'=>'display:none'])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput(['autocomplete'=>'off'])->hint('默认密码:123456') ?>
        </div>
        <div class="col-md-5">
            <?= \common\widgets\Plupload::widget([
                'model'=>$model,
                'attribute'=>'face',
                'url'=>'/file/upload'
            ])?>
            <?= $form->field($model, 'status')->radioList([User::STATUS_ACTIVE=>'可用',User::SCENARIO_DEFAULT=>'禁用']) ?>
            <?= $form->field($model, 'group')->radioList([User::GROUP_READER=>'普通用户',User::GROUP_WRITER=>'作者',User::GROUP_ADMIN=>'管理员'])?>
        </div>
        <div class="col-md-10">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
