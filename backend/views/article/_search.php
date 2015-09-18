<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="col-md-12">
        <div class="col-md-2">
            <?= $form->field($model, 'title') ?>
        </div>
        <div class="col-md-2"><?= $form->field($model, 'category_id') ?></div>
        <div class="col-md-2"><?php  echo $form->field($model, 'author') ?></div>
        <div class="col-md-2"><?php  echo $form->field($model, 'publish_at') ?></div>
        <div class="col-md-3">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
        </div>
    </div>







    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'view_count') ?>

    <?php // echo $form->field($model, 'share') ?>



    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>



    <?php ActiveForm::end(); ?>

</div>
