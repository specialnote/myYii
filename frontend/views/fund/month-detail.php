<?php

?>
<style>
    tr,td,th{height: 20px!important;margin: 0px!important;padding: 0px!important;}
</style>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-hover">
            <thead>
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
