<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '个人设置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">


   <div class="col-md-12">
       <ul class="nav nav-tabs">
           <li role="presentation" class="active"><?= Html::a('用户名',['/user/change','act'=>'username']) ?></li>
           <li role="presentation" ><?= Html::a('密码',['/user/change','act'=>'password']) ?></li>
           <li role="presentation" ><?= Html::a('手机',['/user/change','act'=>'mobile']) ?></li>
           <li role="presentation" ><?= Html::a('邮箱',['/user/change','act'=>'email']) ?></li>
           <li role="presentation" ><?= Html::a('头像',['/user/change','act'=>'face']) ?></li>
       </ul>
   </div>
    <div class="col-md-12">
    <?php $form = ActiveForm::begin(['id'=>'user_change_form'])?>
    <?php
        switch($act){
            case 'username':
    ?>
                <div class="col-md-6">
                    <?= Html::hiddenInput('change','username')?>
                    <?= $form->field($model,'username')->textInput()?>
                </div>
    <?php
                break;
            case 'password':
                break;
            case 'mobile':
                break;
            case 'email':
                break;
            case 'face':
                break;
            default:
                break;
        }
    ?>
    <div class="col-md-8">
        <?= $form->field($model,'verifyCode')->widget(
            yii\captcha\Captcha::className(),
            [
                'captchaAction'=>'/user/captcha',
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
</div>
