<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\FundChoose */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '自选基金', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-choose-view">
    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
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
            'fund_num',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
