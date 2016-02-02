<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\FundChoose */

$this->title = '添加自选';
$this->params['breadcrumbs'][] = ['label' => '自选基金', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-choose-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
