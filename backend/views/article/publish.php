<?php

use yii\helpers\Html;
use common\models\GridView;

$this->title = '批量发布文章';
$this->params['breadcrumbs'][] = ['label' => '文章管理', 'url' => ['/article/index']];
$this->params['breadcrumbs'][] = ['label' => '采集文章管理管理', 'url' => ['/article/gather']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="article-index">
    <p>
        <?= Html::a('批量发布','javascript:void(0)', ['class' => 'btn btn-success','id'=>'publish']) ?>
        <?= Html::a('批量更改文章分类',['/article/category'], ['class' => 'btn btn-primary fr']) ?>
    </p>
    <?= Html::beginForm(['/article/publish'],'post',['id'=>'gather-form']) ?>
    <?= Html::hiddenInput('url',Yii::$app->request->getHostInfo().Yii::$app->request->url) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'header'=>'<input type="checkbox"  id="check-all">',
                'class' => yii\grid\Column::className(),
                'content'=>function ($model){
                    if($model->status == \common\models\Article::STATUS_GATHER){
                        return Html::checkbox('ids[]',false,['class'=>'gather-article','value'=>$model->id]);
                    }else{
                        return '';
                    }
                }
            ],
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
            ],
        ],
        'tableOptions'=>['class' => 'table table-striped table-hover']
    ]); ?>
    <?= Html::endForm() ?>
</div>

<?php $this->registerJs(
<<<STR
    $(function(){
        //全选、反选
        var all = $('#check-all');
        var check = $('.gather-article');
        all.click(function(){
            if(this.checked){
                check.prop('checked',true);
            }else{
                check.prop('checked',false);
            }
        });
        check.click(function(){
            var h = $('.gather-article:checked').length;
            if(h==check.length){
                all.prop('checked',true);
            }else{
                all.prop('checked',false);
            }
        });

        //提交表单
        $('#publish').click(function(){
             var h = $('.gather-article:checked').length;
             if(h>0){
                $('#gather-form').submit();
             }else{
                alert('请选择文章后再提交');
             }
        });
    })
STR


) ?>