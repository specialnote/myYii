<?php
    use yii\helpers\Html;
    \frontend\assets\DateTimePickerAsset::register($this);
    $this->registerJs(<<<STR
    $('#my-element').datepicker();
    var form = $('#fund-sort-time');
    $('#fund-sort-submit').click(function(){
        if(form.find('input[name="start"]').val() && form.find('input[name="end"]').val()){
            $.ajax({
                url:form.attr('action'),
                type:form.attr('method'),
                data:form.serialize(),
                dataType:'json',
                success:function(data){
                    $('#sql').html(data.sql);
                    var html = '';
                    $.each(data.data,function(i,item){
                        html +='<tr><td>'+item.fund_num+'</td><td>'+format(item.rate)+'</td></tr>';
                    });
                    $('.sort-tbody').html(html);
                }
            });
        }
    });
STR
);
?>
<script>
    function format(value) {
        var num = value;
        if (!isNaN(value)) {
            var userreg = /^[0-9]+([.]{1}[0-9]{1,2})?$/;
            if (!userreg.test(value)) {
                var numindex = parseInt(value.indexOf("."), 10);
                var head = value.substring(0, numindex);
                var bottom = value.substring(numindex, numindex + 3);
                var fianlNum = head + bottom;
                num = fianlNum;
            }
        }
        if (num > 5) {
            return '<span style="color: #FC0000">' + num + '</span>';
        } else if (num > 0) {
            return '<span style="color: #FA4B4B">' + num + '</span>';
        } else if (num > -5) {
            return '<span style="color: #039D50">' + num + '</span>';
        } else {
            return '<span style="color: green">' + num + '</span>';
        }
    }
</script>
<div class="row">
    <div class="col-md-12">
        <?= Html::beginForm(['/fund/sort'],'post',['id'=>'fund-sort-time']) ?>
            <?= Html::textInput('start',null,['class'=>'datepicker-here','data-position'=>'right top','data-language'=>'zh-cn','data-date-format'=>'yyyy-mm-dd']) ?>
            <?= Html::textInput('end',null,['class'=>'datepicker-here','data-position'=>'right top','data-language'=>'zh-cn','data-date-format'=>'yyyy-mm-dd']) ?>
            <?= Html::button('查询',['class'=>'btn btn-primary','id'=>'fund-sort-submit']) ?>
        <?= Html::endForm() ?>
    </div>
    <div class="col-md-12" id="sql">

    </div>
    <div class="col-md-12">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>num</th>
                    <th>rate</th>
                </tr>
            </thead>
            <tbody class="sort-tbody">
                <?php if($datas){ ?>
                    <?php foreach($datas as $v){ ?>
                        <tr>
                            <td><?= $v['fund_num'] ?></td>
                            <td><?= \common\models\FundNum::getRate($v['rate']) ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
