<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FundChoose */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fund-choose-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-6">

        <?= $form->field($model, 'fund_num')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
