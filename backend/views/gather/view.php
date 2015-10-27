<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Gather */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gathers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gather-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'url:url',
            'url_org:url',
            'category_id',
            'res',
            'result:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
