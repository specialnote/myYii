<?php
    use yii\widgets\ActiveForm;

    $this->title = '创建节点';
    $this->params['breadcrumbs'][] = ['label'=>'节点','url'=>['/node/index']];
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <?php $form=ActiveForm::begin()?>
        <div class="col-md-6">
            <?= $form->field($model,'name')->textInput(['maxlength'=>true])->hint('节点名称由小写字母开头，3-20位字符（小写字母-_）组成')?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model,'description')->textarea(['rows'=>3])?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model,'parent')->textInput(['maxlength'=>true])->hint('节点名称由小写字母开头，3-20位字符（小写字母-_）组成')?>
        </div>
    <?php ActiveForm::end()?>
</div>
