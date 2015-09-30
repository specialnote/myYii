<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '个人设置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">
   <div class="col-md-12">
       <ul class="nav nav-tabs">
           <li role="presentation" <?= ($act == 'username')?'class="active"':''?>><?= Html::a('用户名',['/site/change','act'=>'username']) ?></li>
           <li role="presentation" <?= ($act == 'password')?'class="active"':''?>><?= Html::a('密码',['/site/change','act'=>'password']) ?></li>
           <li role="presentation" <?= ($act == 'mobile')?'class="active"':''?>><?= Html::a('手机',['/site/change','act'=>'mobile']) ?></li>
           <li role="presentation" <?= ($act == 'email')?'class="active"':''?>><?= Html::a('邮箱',['/site/change','act'=>'email']) ?></li>
           <li role="presentation" <?= ($act == 'face')?'class="active"':''?>><?= Html::a('头像',['/site/change','act'=>'face']) ?></li>
       </ul>
   </div>
    <div class="col-md-12">

    <?php
        switch($act){
            case 'username':
    ?>
                <?php $form = ActiveForm::begin(['id'=>'user_change_form'])?>
                <div class="col-md-6">
                    <?= Html::hiddenInput('change','username')?>
                    <?= $form->field($model,'username')->textInput()?>
                </div>
                <div class="col-md-8">
                    <?= $form->field($model,'verifyCode')->widget(
                        yii\captcha\Captcha::className(),
                        [
                            'captchaAction'=>'/site/captcha',
                            'template' => '<div class="row"><div class="col-md-3">{image}</div><div class="col-md-3">{input}</div></div>',
                            'imageOptions'=>['alt'=>'图片无法加载','title'=>'点击换图', 'style'=>'cursor:pointer'],
                        ]
                    );?>
                    <div class="form-group">
                        <?= Html::submitButton('更 改',['class'=>'btn btn-primary'])?>
                    </div>
                </div>
                <?php ActiveForm::end()?>
    <?php
                break;
            case 'password':
    ?>
                <?php $form = ActiveForm::begin(['id'=>'password_change_form'])?>
                <div class="col-md-6">
                    <?= Html::hiddenInput('change','password')?>
                    <?= $form->field($model,'password')->passwordInput(['value'=>''])?>
                    <?= $form->field($model,'pass1')->passwordInput(['value'=>''])?>
                    <?= $form->field($model,'pass2')->passwordInput(['value'=>''])?>
                </div>
                <div class="col-md-8">
                    <?= $form->field($model,'verifyCode')->widget(
                        yii\captcha\Captcha::className(),
                        [
                            'captchaAction'=>'/site/captcha',
                            'template' => '<div class="row"><div class="col-md-3">{image}</div><div class="col-md-3">{input}</div></div>',
                            'imageOptions'=>['alt'=>'图片无法加载','title'=>'点击换图', 'style'=>'cursor:pointer'],
                        ]
                    );?>
                    <div class="form-group">
                        <?= Html::submitButton('更 改',['class'=>'btn btn-primary'])?>
                    </div>
                </div>
                <?php ActiveForm::end()?>
    <?php
                break;
            case 'mobile':
                break;
            case 'email':
    ?>
                <div class="row">
                    <?php $form = ActiveForm::begin(['id'=>'email-change-form'])?>
                    <div class="col-md-6">
                        <?= Html::hiddenInput('change','email')?>
                        <?= $form->field($model,'email')->textInput(['value'=>$model->email])?>
                    </div>
                    <div class="col-md-7">
                        <?= $form->field($model,'verifyCode')->widget(
                            yii\captcha\Captcha::className(),
                            [
                                'captchaAction'=>'/site/captcha',
                                'template' => '<div class="row"><div class="col-md-3">{image}</div><div class="col-md-3">{input}</div></div>',
                                'imageOptions'=>['alt'=>'图片无法加载','title'=>'点击换图', 'style'=>'cursor:pointer'],
                            ]
                        );?>
                        <div class="form-group">
                            <?= Html::submitButton('更 改',['class'=>'btn btn-primary'])?>
                        </div>
                    </div>
                    <?php ActiveForm::end()?>
                </div>
    <?php
                break;
            case 'face':
                break;
            default:
                break;
        }
    ?>
    </div>
</div>
