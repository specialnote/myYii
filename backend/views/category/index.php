<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章分类';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新建分类', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>'{items} {pager} {summary} ',
        'columns' => [
            'id',
            'name',
            'slug',
            //'description',
            'article_counts',
            'parent',
            'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn','header'=>'<a style="color: #337ab7;cursor: pointer">操作</a>'],
        ],
        'tableOptions'=>['class' => 'table table-striped table-hover']
    ]); ?>

</div>
