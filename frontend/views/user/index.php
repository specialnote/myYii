<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/30 0030
 * Time: 上午 10:53
 */
use yii\helpers\Url;

$this->title = '个人中心';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    td{width:25%;height:25px;}
    tr{height: 25px;}
</style>
<div class="row col-md-12">
    <table class="table table-bordered">
        <tr>
            <td rowspan="2"><img src="<?= $model->face?>" width="100"></td>
            <td>用户名：<?= $model->username?></td>
            <td colspan="2">
                邮箱：<?= $model->email?>
                <?php if($model->email && $model->is_email == 10):?>
                    <a href="<?= Url::to(['/user/category-email'])?>">【立刻验证】</a>
                <?php elseif($model->email && $model->is_email == 20):?>
                    【通过验证】
                <?php endif;?>

            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">
                手机：<?= $model->mobile?>
                <?php if($model->mobile && $model->is_mobile == 10):?>
                    <a href="<?= Url::to(['/user/category-mobile'])?>">【立刻验证】</a>
                <?php elseif($model->mobile && $model->is_mobile == 20):?>
                    【通过验证】
                <?php endif;?>
            </td>
        </tr>
    </table>
</div>

