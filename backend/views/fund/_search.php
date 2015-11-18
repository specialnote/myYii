<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FundSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fund-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'num') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'week') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'quarter') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'three_year') ?>

    <?php // echo $form->field($model, 'all') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
