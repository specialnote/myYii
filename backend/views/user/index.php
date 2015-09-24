<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建用户', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'format'=>['image','options'=>['width'=>'50px']],
                'attribute'=>'face',
                'value'=>function($model){
                    return $model->face;
                }
            ],
            'username',
            'email',
            'mobile',
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return $model->getStatusName();
                }
            ],
            [
                'attribute'=>'group',
                'value'=>function($model){
                    return $model->getGroupName();
                }
            ],
            [
                'attribute'=>'last_login_time',
                'value'=>function($model){
                     return $model->last_login_time?date('Y-m-d H:i',strtotime($model->last_login_time)):'';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {delete} {options}',
                'options'=>['width'=>'100'],
                'buttons'=>[
                    'options'=>function($url,$model,$key){
                        return Html::a('权限',['/user/role','id'=>$model->id]);
                    }
                ],
            ],
        ],
    ]); ?>

</div>
