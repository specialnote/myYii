<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建文章', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute'=>'cover_img',
                'format'=>['image',['width'=>'50']],
                'value'=>function($model){
                    return $model->cover_img;
                }
            ],
            'title',
           // 'content:ntext',
            [
                'attribute'=>'category_id',
                'value'=>function($model){
                    return \common\models\Category::getCategoryName($model->category_id);
                }
            ],
             'author',
             'publish_at',
            'view_count',
            'share',
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return $model->getStatusName($model->status);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {delete}',
                'options'=>['width'=>'75'],
            ],
        ],
        'tableOptions'=>['class' => 'table table-striped table-hover']
    ]); ?>

</div>
