<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
?>
<div class="row">
   <!-- <div class="col-md-8">
        <?/*= Html::beginForm() */?>
            <?/*= Html::activeTextInput($model,'username') */?>
            <?/*= Html::activeTextInput($model,'mobile',['maxlength'=>11]) */?>
            <?/*= Html::activeTextInput($model,'email') */?>
            <?/*= Html::activePasswordInput($model,'pass') */?>
            <?/*= Html::activePasswordInput($model,'confirm') */?>
            <?/*= Html::submitButton('注册') */?>
        <?/*= Html::endForm() */?>
    </div>-->
    <h1>ActiveForm 生成的表单能够自动向后台验证</h1>
    <div class="col-md-8">
        <?php $form =  ActiveForm::begin() ?>
            <?= $form->field($model,'username')->textInput() ?>
            <?= $form->field($model,'mobile')->textInput(['maxlength'=>11]) ?>
            <?= $form->field($model,'email')->textInput() ?>
            <?= $form->field($model,'pass')->passwordInput() ?>
            <?= $form->field($model,'confirm')->passwordInput() ?>
            <?= Html::submitButton('注册',['class'=>'form-control']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>
