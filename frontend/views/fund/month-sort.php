<?php
    use yii\helpers\Url;
    use common\models\FundNum;
?>
<style>
    #month-sort-time{list-style: none;}
    #month-sort-time li{float: left;margin-right: 20px;}
</style>
<div class="row">
    <h1><?= $year ?> - <?= $month ?></h1>
</div>
<div>
    <ul id="month-sort-time">
        <?php for($i=1;$i<=12;$i++){ ?>
            <li><a href="<?= Url::to(['/fund/month-sort','year'=>2015,'month'=>$i]) ?>">2015-<?= $i ?></a></li>
        <?php }?>
        <?php for($i=1;$i<=date('m');$i++){ ?>
            <li><a href="<?= Url::to(['/fund/month-sort','year'=>2015,'month'=>$i]) ?>">2016-<?= $i ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="row">
    <table id="month-sort-ul"  class="table table-striped table-hover">
        <?php foreach($posts as $v){ ?>
            <tr>
                <td><?= $v['fund_num'] ?></td>
                <td><?= FundNum::getRate($v['rate']) ?></td>
                <td><a target="_blank" href="<?= Url::to(['/fund/month-detail','num'=>$v['fund_num']]) ?>">月详情</a></td>
                <td><a target="_blank" href="<?= Url::to(['/fund/week-detail','num'=>$v['fund_num']]) ?>">周详情</a></td>
                <td><a target="_blank" href="<?= Url::to(['/fund/day-detail','num'=>$v['fund_num']]) ?>">日详情</a></td>
            </tr>
        <?php }?>
    </table>
</div>
