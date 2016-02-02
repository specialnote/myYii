<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FundChoose */

$this->title = '更新自选: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '自选基金', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="fund-choose-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
