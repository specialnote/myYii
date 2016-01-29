//根据条件对基金进行筛选
$('#fund-filter-submit').click(function(){
    var form = $('#fund-filter-form');
    var input = $('#fund-filter-form input:checked');
    if(input.length){
        $.post(fund_filter,form.serialize(), function (data) {
            var html = '';
            var num = '<tr>';
            if(data){
                for(var i=0;i<data.length;i++){
                    html+='<tr><td>'+data[i]+'</td><td><a href="/fund/day-detail?num='+data[i]+'" target="_blank">日详情</a></td><td><a href="/fund/week-detail?num='+data[i]+'" target="_blank">周详情</a></td><td> <a href="/fund/month-detail?num='+data[i]+'" target="_blank">月详情</a></td></tr>';
                    num+='<td>'+data[i]+'</td>';
                }
                num+='</tr>';
            }
            $('#fund-filter-content').html(html);
            $('#fund-filter-num').html(num);
            $('#month-detail-filter').show();
            $('#week-detail-filter').show();
            $('#day-detail-filter').show();
        });
        $('#fund-filter-form')
    }else{
        alert('请选择条件');
    }
});
//按照指定条件获取月分析
$('#month-detail-filter').click(function () {
    var td = $('#fund-filter-num tr td');
    var num = [];
    for(var i=0;i<td.length;i++){
        num[i]= $(td[i]).text();
    }
    $.post(month_detail_filter,{'nums':num},function(data){
        if(data){
            var html = '<tr><td colspan="4">月详情处理</td></tr>';
            for(var j=0;j<data.length;j++){
                var v= data[j];
                html +='<tr><td>'+v.fund_num+'</td><td>'+v.type+'</td><td>均值：'+v.average+'</td><td>标准差：'+v.sd+'</td></tr>';
            }
            alert('加载成功');
            $('#fund-monty-detail-filter').html(html);
        }
    });
});
//按照指定条件获取月分析
$('#week-detail-filter').click(function () {
    var td = $('#fund-filter-num tr td');
    var num = [];
    for(var i=0;i<td.length;i++){
        num[i]= $(td[i]).text();
    }
    $.post(week_detail_filter,{'nums':num},function(data){
        if(data){
            var html = '<tr><td colspan="4">周详情处理</td></tr>';
            for(var j=0;j<data.length;j++){
                var v= data[j];
                html +='<tr><td>'+v.fund_num+'</td><td>'+v.type+'</td><td>均值：'+v.average+'</td><td>标准差：'+v.sd+'</td></tr>';
            }
            alert('加载成功');
            $('#fund-week-detail-filter').html(html);
        }
    });
});
//按照指定条件获取月分析
$('#day-detail-filter').click(function () {
    var td = $('#fund-filter-num tr td');
    var num = [];
    for(var i=0;i<td.length;i++){
        num[i]= $(td[i]).text();
    }
    $.post(day_detail_filter,{'nums':num},function(data){
        if(data){
            var html = '<tr><td colspan="4">日详情处理</td></tr>';
            for(var j=0;j<data.length;j++){
                var v= data[j];
                html +='<tr><td>'+v.fund_num+'</td><td>'+v.type+'</td><td>均值：'+v.average+'</td><td>标准差：'+v.sd+'</td></tr>';
            }
            alert('加载成功');
            $('#fund-day-detail-filter').html(html);
        }
    });
});