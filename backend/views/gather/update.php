<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Gather */

$this->title = 'Update Gather: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gathers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gather-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
