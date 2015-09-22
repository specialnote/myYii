<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = '角色';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-index">


    <p>
        <?= Html::a('新建角色', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>角色名称</th>
                <th>角色描述</th>
                <th>操 作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($roles as $v){?>
            <tr>
                <td><?= $v->name?></td>
                <td><?= $v->description?></td>
                <td style="width: 60px;">
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',['/role/update','name'=>$v->name])?>
                    &nbsp;
                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>',['/role/delete','name'=>$v->name],[
                        'data' => [
                            'confirm' => '确认删除吗？',
                            'method' => 'post',
                        ]
                    ])?>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>


</div>
