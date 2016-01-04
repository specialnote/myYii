<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Article;
use dosamigos\datepicker\DatePicker;
use kucha\ueditor\UEditor;
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

        <?php
            echo $form->field($model,'content')->widget('kucha\ueditor\UEditor',[
                'clientOptions' => [
                    //编辑区域大小
                    'initialFrameHeight' => '400',
                    //设置语言
                    'lang' =>'zh-cn', //中文为 zh-cn
                    //定制菜单

            ]]);
        ?>
    </div>
    <div class="col-md-4">

        <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
        <?= \common\widgets\Plupload::widget([
            'model'=>$model,
            'attribute'=>'cover_img',
            'url'=>'/file/upload',
            'path'=>Yii::$app->request->hostInfo,
        ])?>

        <?= $form->field($model, 'status')->radioList([Article::STATUS_DISPLAY=>'可用',Article::STATUS_HIDDEN=>'禁用']) ?>

        <?= Html::activeLabel($model,'publish_at') ?>
        <?= DatePicker::widget([
            'model' => $model,
            'attribute' => 'publish_at',
            'template' => '{input} {addon}',
            'language'=>'zh-CN',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'weekStart'=>'1',
            ]
        ]);?>
        <?php if(Yii::$app->controller->action->id == 'create'): ?>
        <?= $form->field($model,'tag')->textInput()->hint('多个标签用;隔开') ?>
        <?php else: ?>
            <?= Html::activeLabel($model,'tag')?>
            <?php
                echo Html::activeTextInput($model,'tag',['value'=>$tag,'disabled'=>true])
            ?>
        <?php endif; ?>
    </div>
    <div class="form-group col-md-12">
        <?= Html::submitButton($model->isNewRecord ? '新建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
