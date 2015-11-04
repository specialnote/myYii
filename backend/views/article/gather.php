<?php

use yii\helpers\Html;
use common\models\GridView;

$this->title = '采集文章管理';
$this->params['breadcrumbs'][] = ['label' => '文章管理', 'url' => ['/article/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="article-index">
    <p>
        <?= Html::a('批量发布文章',['/article/publish'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('批量更改文章分类',['/article/category'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute'=>'id',
                'value'=>function($model){
                    return $model->id;
                },
                'headerOptions'=>['width'=>'50px'],
            ],
            [
                'attribute'=>'category_id',
                'value'=>function($model){
                    return $model->getCategoryName();
                }
            ],
            [
                'header'=>'<font style="color: #337ab7">标签</font>',
                'class' => yii\grid\Column::className(),
                'content'=>function ($model){
                    return $model->getArticleTagToString();
                }
            ],
            [
                'attribute'=>'title',
                'label'=>'文章标题',
                'format'=>'html',
                'value'=>function($model){
                    return '<b>'.$model->title.'</b>';
                },
            ],
            [
                'attribute'=>'publish_at',
                'value'=>function($model){
                    return $model->publish_at;
                },
                'headerOptions'=>['width'=>'100px'],
            ],
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return $model->getStatusName($model->status);
                },
                'headerOptions'=>['width'=>'100px'],
                'filter'=>\common\models\Article::get_status(),
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header'=>'<font style="color: #337ab7">操作</font>',
                'template' => '{preview} {update} {delete}',
                'buttons' => [
                    'preview' => function ($url, $model, $key) {
                        return  Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => '预览'] ) ;
                    },
                ],
                'headerOptions' => ['width' => '75']
            ],
        ],
        'tableOptions'=>['class' => 'table table-striped table-hover']
    ]); ?>
    <?= Html::endForm() ?>
</div>
