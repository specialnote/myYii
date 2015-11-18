<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
<div class="fund-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'num',
            'date',
            [
                'attribute'=>'week',
                'format'=>'html',
                'value'=>function($model){
                    $value = $model->week;
                    if($value <0){
                        return  '<span style="color: green">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }else{
                        return  '<span style="color: red">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }
                }
            ],
            [
                'attribute'=>'month',
                'format'=>'html',
                'value'=>function($model){
                    $value = $model->month;
                    if($value <0){
                        return  '<span style="color: green">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }else{
                        return  '<span style="color: red">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }
                }
            ],
            [
                'attribute'=>'quarter',
                'format'=>'html',
                'value'=>function($model){
                    $value = $model->quarter;
                    if($value <0){
                        return  '<span style="color: green">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }else{
                        return  '<span style="color: red">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }
                }
            ],
            [
                'attribute'=>'year',
                'format'=>'html',
                'value'=>function($model){
                    $value = $model->year;
                    if($value <0){
                        return  '<span style="color: green">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }else{
                        return  '<span style="color: red">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }
                }
            ],
            [
                'attribute'=>'three_year',
                'format'=>'html',
                'value'=>function($model){
                    $value = $model->three_year;
                    if($value <0){
                        return  '<span style="color: green">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }else{
                        return  '<span style="color: red">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }
                }
            ],
            [
                'attribute'=>'all',
                'format'=>'html',
                'value'=>function($model){
                    $value = $model->all;
                    if($value <0){
                        return  '<span style="color: green">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }else{
                        return  '<span style="color: red">'.Yii::$app->formatter->asPercent($value).'</span>';
                    }
                }
            ],
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn','template'=>'{view}'],
        ],
        'tableOptions'=>['class' => 'table table-striped table-hover']
    ]); ?>

</div>
