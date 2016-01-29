<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    $this->title = '基金筛选';
?>
<div class="row">
    <?= Html::beginForm(['/api/fund-filter'],'post',['id'=>'fund-filter-form']) ?>
        <table>
            <tr>
                <?php foreach($types as $k=>$v): ?>
                    <td><input type="checkbox" name="type[]" value="<?= $k ?>" id="<?= $k ?>" <?= in_array($k,$init)?'checked':'' ?> /><label for="<?= $k ?>"><?= $v ?></label></td>
                <?php endforeach; ?>
                <td><?= Html::button('查询',['id'=>'fund-filter-submit']) ?></td>
                <td><?= Html::button('月分析',['id'=>'month-detail-filter']) ?></td>
                <td><?= Html::button('周分析',['id'=>'week-detail-filter']) ?></td>
                <td><?= Html::button('日分析',['id'=>'day-detail-filter']) ?></td>
            </tr>
        </table>
    <?= Html::endForm(); ?>
</div>
<div class="row">
    <div class="col-md-12"> <table id="fund-filter-num"></table></div>
    <div class="col-md-3"><table id="fund-monty-detail-filter"></table></div>
    <div class="col-md-3"><table id="fund-week-detail-filter"></table></div>
    <div class="col-md-3"><table id="fund-day-detail-filter"></table></div>
    <div class="col-md-3"><table id="fund-filter-content"></table></div>
</div>
