<?php
$this->title = $num;
?>
<style>
    tr,td,th{height: 20px!important;margin: 0px!important;padding: 0px!important;}
</style>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <td colspan="3" style="text-align: center;font-weight: 700;">
                    月涨幅记录--编号：<?= $num ?>；
                    类型：<?= \common\models\FundNum::getTypeName($num) ?>；
                    均值：<?= number_format($average,2) ?>；
                    标准差：<?= number_format($sd,2) ?>；
                    比例：<?= number_format($sd/$average,2) ?>；
                    总计：<?= number_format($sum,2) ?>
                </td>
            </tr>
            <tr>
                <th>年</th>
                <th>月</th>
                <th>幅度</th>
            </tr>
            </thead>
           <tbody>
           <?php foreach($datas as $data){ ?>
               <tr>
                   <td><?= $data['year'] ?></td>
                   <td><?= $data['month'] ?></td>
                   <td><?= \common\models\FundNum::getRate($data['sum_rate'])?></td>
               </tr>
           <?php } ?>
           </tbody>
        </table>
    </div>
</div>
