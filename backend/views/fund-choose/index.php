<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\FundNum;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '自选基金';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-choose-index">

    <p>
        <?= Html::a('添加自选', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'fund_num',
            [
                'attribute'=>'created_at',
                'value'=>function($model){
                    return date('Y-m-d',$model->created_at);
                }
            ],
            [
                'header'=>'累计涨幅',
                'content'=>function($model){
                    return FundNum::getRate($model->getTotalRate());
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
