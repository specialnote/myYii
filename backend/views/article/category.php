<?php

use yii\helpers\Html;
use common\models\GridView;
use yii\helpers\ArrayHelper;
use common\models\Category;

$this->title = '批量更改文章分类';
$this->params['breadcrumbs'][] = ['label' => '文章管理', 'url' => ['/article/index']];
$this->params['breadcrumbs'][] = ['label' => '采集文章管理管理', 'url' => ['/article/gather']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="article-index">
    <?= Html::beginForm(['/article/category'],'post',['id'=>'category-form']) ?>

    <p>
        <?= Html::dropDownList('category_id',null,ArrayHelper::map(Category::find()->where(['status'=>Category::STATUS_DISPLAY])->all(),'id','name'),['prompt'=>'无分类','id'=>'category','class'=>'form-control']) ?>
        <?= Html::a('批量更改文章分类','javascript:void(0)', ['class' => 'btn btn-success','id'=>'category-submit']) ?>
        <?= Html::a('批量发布',['/article/publish'], ['class' => 'btn btn-primary fr']) ?>
    </p>

    <?= Html::hiddenInput('url',Yii::$app->request->getHostInfo().Yii::$app->request->url) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'header'=>'<input type="checkbox"  id="check-all">',
                'class' => yii\grid\Column::className(),
                'content'=>function ($model){
                    if($model->category_id == 0){
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
        $('#category-submit').click(function(){
             var h = $('.gather-article:checked').length;
             var id = $('#category').val();
             console.log(h);
             if(h>0){
                if(id!=0){
                    $('#category-form').submit();
                }else{
                    alert('请选择分类');
                }
             }else{
                alert('请选择文章后再提交');
             }
        });
    })
STR


) ?>

<?php $this->registerCss(<<<STR
    #category{
        display: block;
        width: 20%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }
STR
) ?>
