<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows'=>3,'maxlength'=>true]) ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? '新建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="col-md-4">
            <?=\common\widgets\CategoryDropDownList::widget([
                'model'=>$model,
                'attribute'=>'parent',
                'parent'=>$model->parent,
                'options'=>[
                    'class'=>'form-control',
                ],
                'currentOptionDisabled'=>(Yii::$app->controller->action->id == 'update')?true:false,
            ])?>
            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status')->radioList([Category::STATUS_DISPLAY=>'可用',Category::STATUS_HIDE=>'禁用']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
