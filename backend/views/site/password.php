<?php
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
?>
<div class="col-md-6">
    <?php $form = ActiveForm::begin() ?>
    <?= $form->field($model,'password')->passwordInput() ?>
    <?= $form->field($model,'pass1')->passwordInput() ?>
    <?= $form->field($model,'pass2')->passwordInput() ?>
    <?= $form->field($model,'verifyCode')->widget(
        yii\captcha\Captcha::className(),
        [
            'template' => '<div class="row"><div class="col-md-3">{image}</div><div class="col-md-3">{input}</div></div>',
            'imageOptions'=>['alt'=>'图片无法加载','title'=>'点击换图', 'style'=>'cursor:pointer'],
        ]
    );?>

    <?= Html::submitButton('更改密码',['class'=>'btn btn-primary']) ?>

    <?php ActiveForm::end()?>
</div>
