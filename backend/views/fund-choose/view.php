<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\FundNum;

/* @var $this yii\web\View */
/* @var $model common\models\FundChoose */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '自选基金', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-choose-view">
    <h2><?= $model->fund_num ?></h2>
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
    <?php
        $day = FundNum::getFundDetail($model->fund_num,'day');
        if($day){
             $h1 = '均值：'.number_format($day['average'],2).'；标准差：'.number_format($day['sd'],2).'；比例：'.($day['average']?number_format($day['sd']/$day['average'],2):'');
        }else{
             $h1 = '-';
        }
        $week = FundNum::getFundDetail($model->fund_num,'week');
        if($week){
            $h2 = '均值：'.number_format($week['average'],2).'；标准差：'.number_format($week['sd'],2).'；比例：'.($week['average']?number_format($week['sd']/$week['average'],2):'');
        }else{
            $h2 = '-';
        }
        $month = FundNum::getFundDetail($model->fund_num,'month');
        if($month){
            $h3 = '均值：'.number_format($month['average'],2).'；标准差：'.number_format($month['sd'],2).'；比例：'.($month['average']?number_format($month['sd']/$month['average'],2):'');
        }else{
            $h3 = '-';
        }
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fund_num',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'label'=>'日统计',
                'format'=>'html',
                'value'=>$h1
            ],
            [
                'label'=>'周统计',
                'format'=>'html',
                'value'=>$h2
            ],
            [
                'label'=>'月统计',
                'format'=>'html',
                'value'=>$h3
            ],
        ],
    ]) ?>

</div>
