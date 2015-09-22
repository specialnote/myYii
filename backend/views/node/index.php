<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = '权限节点';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-index">


    <p>
        <?= Html::a('新建节点', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>节点名称</th>
                <th>节点描述</th>
                <th>操 作</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($nodes as $v){?>
            <tr>
                <td><?= $v->name?></td>
                <td><?= $v->description?></td>
                <td style="width: 50px;">
                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>',['/node/delete','name'=>$v->name])?>
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',['/node/update','name'=>$v->name])?>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>


</div>
