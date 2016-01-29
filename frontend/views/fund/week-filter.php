<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<style>
    #week-filter-list,#num-list,#week-data ul{list-style: none;}
    #week-filter-list li ,#num-list li{float: left;}
    #week-data li .fund-num{margin-right: 5px;}
    #num-list li {margin-right: 10px;float: none;}
</style>
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
        <?= Html::beginForm(['/fund/get-week-filter'],'post',['id'=>'week-filter']) ?>
            <ul id="week-filter-list">
                <?php foreach($times as $v){ ?>
                    <li>
                        <input type="checkbox" id="<?= $v ?>" name="w[]" value="<?= $v ?>" class="w-check">
                        <label for="<?= $v ?>"><?= $v ?></label>
                    </li>
                <?php } ?>
                <li>
                    <?= Html::button('查询',['class'=>'btn btn-primary']) ?>
                </li>
            </ul>
        <?= Html::endForm() ?>
    </div>
    <div class="col-md-12">
        <ul id="num-list"></ul>
    </div>
    <div class="col-md-12" id="week-data">

    </div>
</div>
<?php
    $this->registerJs(<<<STR
     var form = $('#week-filter');
        form.find('.btn-primary').click(function(){
            var w = form.find('.w-check:checked');
            if(w.length>0){
                 $.post(get_week_filter,form.serialize(),function(data){
                    if(data.length >0 ){
                        var html = '';
                        for(var i=0;i<data.length;i++){
                            var v = data[i];
                            if(v.length>0){
                                html +='<div class=\'col-md-2\'><ul>';
                                for(var j=0;j<v.length;j++){
                                    html +='<li><span class=\'fund-num\'>';
                                    html +=v[j].fund_num;
                                    html +='</span><span class=\'rate\'>';
                                    html +=format(v[j].rate);
                                    html +='</span></li>';
                                }
                                html +='</ul></div>';
                            }
                        }
                        $('#week-data').html(html);
                    }

                 });
                 $.post(get_week_duplicate,form.serialize(),function(data){
                     var html = '';
                    if(data.length >0 ){
                        for(var i=0;i<data.length;i++){
                              html +='<li><a href="/fund/day-detail?num='+data[i]+'" target="_blank">日详情：'+data[i]+'</a> --- <a href="/fund/week-detail?num='+data[i]+'" target="_blank">周详情：'+data[i]+'</a> --- <a href="/fund/month-detail?num='+data[i]+'" target="_blank">月详情：'+data[i]+'</a></li>';
                        }
                    }
                    $('#num-list').html(html);
                 });
            }else{
                alert('请选择时间');
            }
        });
STR
);
?>
