<?php
    use yii\helpers\Url;
    use common\models\FundNum;
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
                    var html = '';
                    var _fund = '<tr>';
                    var num = '';
                    $.each(data,function(i,item){
                        html +='<tr><td>'+item.fund_num+'</td><td>'+format(item.rate)+'</td></tr>';
                        _fund += '<td>'+item.fund_num+'</td>';
                       num +=item.fund_num+'_';
                    });
                    num += '##';
                    $('#duplicate-num').html($('#duplicate-num').html() + num);
                    _fund += '</tr>';
                    $('#fund-num').append(_fund);
                    $('.sort-tbody').html(html);

                }
            });
        }
    });

      $('#duplicate').click(function(){
            duplicate($('#duplicate-num').html());
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
    function duplicate(num){
        $.post(fund_duplicate,{'num':num},function(data){
            if(data.code){
                var html = '<ul>';
                $.each(data.msg,function(i,item){
                    html +='<li><a href="/fund/day-detail?num='+ $.trim(item)+'" target="_blank">日详情：'+$.trim(item)+'</a> --- <a href="/fund/week-detail?num='+$.trim(item)+'" target="_blank">周详情：'+$.trim(item)+'</a> --- <a href="/fund/month-detail?num='+$.trim(item)+'" target="_blank">月详情：'+$.trim(item)+'</a></li>';
                });
                html+='</ul>';
                $('#has-duplicate-num').html(html);
            }
        });
    }
</script>
<style type="text/css">
    #fund-num td{    border: 1px solid #ccc;}
    #fund-type{list-style: none;}
    #fund-type li{float: left;margin-right: 20px;}
</style>
<div class="row">
    <ul id="fund-type">
        <li><a href="<?= Url::to(['/fund/sort','type'=>FundNum::TYPE_GG]) ?>">股票</a></li>
        <li><a href="<?= Url::to(['/fund/sort','type'=>FundNum::TYPE_HH]) ?>">混合</a></li>
        <li><a href="<?= Url::to(['/fund/sort','type'=>FundNum::TYPE_ZQ]) ?>">债券</a></li>
        <li><a href="<?= Url::to(['/fund/sort','type'=>FundNum::TYPE_ZS]) ?>">指数</a></li>
        <li><a href="<?= Url::to(['/fund/sort','type'=>FundNum::TYPE_BB]) ?>">保本</a></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <?= Html::beginForm(['/fund/sort','type'=>$type],'post',['id'=>'fund-sort-time']) ?>
            <?= Html::textInput('start',null,['class'=>'datepicker-here','data-position'=>'right top','data-language'=>'zh-cn','data-date-format'=>'yyyy-mm-dd']) ?>
            <?= Html::textInput('end',null,['class'=>'datepicker-here','data-position'=>'right top','data-language'=>'zh-cn','data-date-format'=>'yyyy-mm-dd']) ?>
            <?= Html::button('查询',['class'=>'btn btn-primary','id'=>'fund-sort-submit']) ?>
        <?= Html::endForm() ?>
    </div>
    <table id="fund-num">

    </table>
    <div>
        <div id="has-duplicate-num"></div>
        <textarea style="display: none;" id="duplicate-num"> </textarea>
        <button class="btn btn-primary" id="duplicate">查重</button>
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
