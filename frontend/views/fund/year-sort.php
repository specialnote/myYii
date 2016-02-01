<?php
    use yii\helpers\Url;
    use common\models\FundNum;
?>
<style>
    #month-sort-time{list-style: none;}
    #month-sort-time li{float: left;margin-right: 20px;}
</style>
<div class="row">
    <h1><?= $year ?></h1>
</div>
<div>
    <ul id="month-sort-time">
        <li><a href="<?= Url::to(['/fund/year-sort','year'=>2016]) ?>">2016</a></li>
        <li><a href="<?= Url::to(['/fund/year-sort','year'=>2015]) ?>">2015</a></li>
        <li><a href="<?= Url::to(['/fund/year-sort','year'=>2014]) ?>">2014</a></li>
    </ul>
</div>
<div class="row">
    <table id="month-sort-ul"  class="table table-striped table-hover">
        <?php foreach($posts as $v){ ?>
            <tr>
                <td><?= $v['fund_num'] ?></td>
                <td><?= FundNum::getRate($v['r']) ?></td>
                <td><a target="_blank" href="<?= Url::to(['/fund/month-detail','num'=>$v['fund_num']]) ?>">月详情</a></td>
                <td><a target="_blank" href="<?= Url::to(['/fund/week-detail','num'=>$v['fund_num']]) ?>">周详情</a></td>
                <td><a target="_blank" href="<?= Url::to(['/fund/day-detail','num'=>$v['fund_num']]) ?>">日详情</a></td>
            </tr>
        <?php }?>
    </table>
</div>
