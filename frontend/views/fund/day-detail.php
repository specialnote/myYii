<?php
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;
    use yii\helpers\Html;
    \frontend\assets\HighStockAsset::register($this);
?>
<style>
    tr,td,th{height: 20px!important;margin: 0px!important;padding: 0px!important;}
</style>
<script>
    var rate_day_rate_data_url = "<?= Url::to(['/api/rate-day-detail-data','num'=>$num]) ?>";
    var price_day_rate_data_url = "<?= Url::to(['/api/price-day-detail-data','num'=>$num]) ?>";
</script>
<div class="row">
    <div id="day-rate-container" style="min-width:400px;height:400px"></div>
</div>
<div class="row">
    <div id="day-price-container" style="min-width:400px;height:400px"></div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <td colspan="2" style="text-align: center;font-weight: 700;">
                    周涨幅记录--编号：<?= $num ?>；
                    类型：<?= \common\models\FundNum::getTypeName($num) ?>；
                    均值：<?= number_format($average,2) ?>；
                    标准差：<?= number_format($sd,2) ?>
                </td>
            </tr>
            <tr>
                <th>日期</th>
                <th>幅度</th>
            </tr>
            </thead>
           <tbody>
           <?php foreach($datas as $data){ ?>
               <tr>
                   <td><?= $data['date'] ?></td>
                   <td><?= \common\models\FundNum::getRate($data['rate'])?></td>
               </tr>
           <?php } ?>
           </tbody>
        </table>
    </div>
</div>
