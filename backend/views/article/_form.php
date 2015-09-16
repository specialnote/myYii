<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Article;
/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form col-md-12">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-8">
        <?= \common\widgets\CategoryDropDownList::widget([
            'model'=>$model,
            'attribute'=>'category_id',
            'options'=>[
                'class'=>'form-control',
                'style'=>'width:40%'
            ],
        ])?>
        <?= $form->field($model, 'title')->textarea(['maxlength' => true,'rows'=>2]) ?>
        <?= \common\widgets\UEditor::widget([
            'model'=>$model,
            'attribute'=>'content',
            'name'=>'Article[content]'
        ])?>
        <!--<?/*= $form->field($model, 'content')->textarea(['rows' => 6]) */?>-->
    </div>
    <div class="col-md-4">

        <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'cover_img')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->radioList([Article::STATUS_DISPLAY=>'可用',Article::STATUS_HIDDEN=>'禁用']) ?>

        <?= $form->field($model, 'publish_at')->textInput() ?>

    </div>
    <div class="form-group col-md-12">
        <?= Html::submitButton($model->isNewRecord ? '新建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
