<?php

use yii\helpers\Html;
use common\models\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="article-index">
    <?php /* echo $this->render('_search', ['model' => $searchModel]); */?>

    <p>
        <?= Html::a('新建文章', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('采集文章管理',['/article/gather'], ['class' => 'btn btn-primary fr']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute'=>'cover_img',
                'format'=>['image',['width'=>'50']],
                'value'=>function($model){
                    return $model->cover_img;
                }
            ],
            [
                'attribute'=>'title',
                'label'=>'文章标题',
                'format'=>'html',
                'value'=>function($model){
                    return '<b>'.$model->title.'</b>';
                },
                'headerOptions'=>['width'=>'300px'],
            ],
            [
                'attribute'=>'category_id',
                'label'=>'文章分类',
                'value'=>function($model){
                    return \common\models\Category::get_category_result($model->category_id);
                },
                'filter'=>\common\models\Category::get_category(),
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
                'header'=>'操作',
                'template'=>'{view} {update} {delete}',
                'headerOptions'=>['width'=>'75'],
            ],
        ],
        'tableOptions'=>['class' => 'table table-striped table-hover']
    ]); ?>

</div>
