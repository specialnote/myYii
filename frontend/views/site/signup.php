<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\User;

$this->title = '注册';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['maxlength'=>true]) ?>

                <?= $form->field($model, 'email')->textInput(['maxlength'=>true]) ?>

                <?= $form->field($model, 'mobile')->textInput(['maxlength'=>true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'confirm')->passwordInput() ?>

                <?= $form->field($model, 'group')->radioList([User::GROUP_READER=>'普通用户',User::GROUP_WRITER=>'作者',User::GROUP_ADMIN=>'管理员']) ?>

                <div class="form-group">
                    <?= Html::submitButton('注册', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
