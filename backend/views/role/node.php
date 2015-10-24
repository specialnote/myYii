<?php

use yii\helpers\Html;


$this->title = '权限';
$this->params['breadcrumbs'][] = ['label'=>'角色','url'=>['/role/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-index col-md-6">
    <?= Html::beginForm(['/role/node','name'=>$name],'post')?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= Html::checkbox('check',false,['id'=>'check_all'])?></th>
                <th>权限名称</th>
                <th>标书</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($nodes as $v){?>
            <tr>
                <td><?= Html::checkbox('node[]',in_array($v->name,$roleNodes)?true:false,['value'=>$v->name,'class'=>'node_check'])?></td>
                <td><?= $v->name?></td>
                <td><?= $v->description?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div>
        <?= Html::submitButton('提交',['class'=>'btn btn-primary'])?>
    </div>
    <?= Html::endForm()?>

</div>
<?php
    $this->registerJs(
        <<<ENT
       $('#check_all').click(function(){
            if($(this).is(':checked')){
                $('.node_check').attr('checked',true);
            }else{
                $('.node_check').attr('checked',false);
            }
        });
ENT
    );
?>