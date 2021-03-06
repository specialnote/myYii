<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FundDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fund Datas';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    tbody tr{padding: 0px;height: 20px;}
    tbody tr td{height: 20px;line-height: 20px;margin: 0px;padding: 0px!important;}
</style>
<div class="fund-data-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
   <!-- <p>
        <?/*= Html::a('抓取', ['import'], ['class' => 'btn btn-success']) */?>
    </p>-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'fund_num',
            'date',
            'iopv',
            'accnav',
             'growth',
             'rate',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn','template'=>'{view}'],
        ],
        'tableOptions'=>['class' => 'table table-striped table-hover']
    ]); ?>

</div>
