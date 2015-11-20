<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FundSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Funds';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    tbody tr{padding: 0px;height: 20px;}
    tbody tr td{height: 20px;line-height: 20px;margin: 0px;padding: 0px!important;}
</style>
<p>
    <?= Html::a('周平均数分析', ['/fund/week'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('月平均数分析',['/fund/month'], ['class' => 'btn btn-primary ']) ?>
</p>
<div class="fund-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'num',
            'date',
            'week',
            'month',
            'quarter',
            'year',
            'three_year',
            'all',
            // 'created_at',
            // 'updated_at',

            [
                'label'=>'操作',
                'format'=>'raw',
                'value' => function($data){
                    return Html::a('数据', Url::to(['/fund/data','num'=>trim($data->num)]), ['title' => '数据']).' | '.Html::a('分析',Url::to(['/fund/assize','num'=>trim($data->num)]));
                }
            ],
        ],
        'tableOptions'=>['class' => 'table table-striped table-hover']
    ]); ?>

</div>
