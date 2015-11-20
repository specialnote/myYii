<?php
    use yii\helpers\Url;
    use yii\helpers\Html;

    $this->title = 'Fund Datas Assize:'.$num;
    $this->params['breadcrumbs'][] = ['label'=>'Fund Data','url'=>['/fund/data','num'=>$num]];
    $this->params['breadcrumbs'][] = $this->title;
?>
<style>
    table{width: 100%;}
    td{min-width: 40px;}
</style>
<div class="row">
    <h2>每月数据分析</h2>
    <table>
        <thead>
            <tr>
                <th>year</th>
                <th>month</th>
                <th>iopv</th>
                <th>growth</th>
                <th>rate(%)</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($month_data as $year=>$v1){
                foreach($v1 as $v2){
                    echo '<tr>';
                    echo '<td>';
                    echo $year;
                    echo '</td>';
                    echo '<td>';
                    echo $v2['month'];
                    echo '</td>';
                    echo '</td>';
                    echo '<td>';
                    echo $v2['month_avg_iopv'];
                    echo '</td>';
                    echo '<td>';
                    echo $v2['month_avg_growth'];
                    echo '</td>';
                    echo '<td>';
                    echo $v2['month_avg_rate'];
                    echo '</td>';
                    echo '</tr>';
                }
                echo '<tr><td colspan="4" style="background: #ddd;height: 2px;"></td></tr>';
            }
        ?>
        </tbody>
    </table>
</div>
<div class="row">
    <h2>每周数据分析</h2>
    <table>
        <thead>
        <tr>
            <th>year</th>
            <th>month</th>
            <th>week</th>
            <th>iopv</th>
            <th>growth</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($week_data as $year=>$v1){
            foreach($v1 as $month=>$v2){
                foreach($v2 as $v3){
                    echo '<tr>';
                    echo '<td>';
                    echo $year;
                    echo '</td>';
                    echo '<td>';
                    echo $month;
                    echo '</td>';
                    echo '<td>';
                    echo $v3['week'];
                    echo '</td>';
                    echo '</td>';
                    echo '<td>';
                    echo $v3['week_avg_iopv'];
                    echo '</td>';
                    echo '<td>';
                    echo   Yii::$app->formatter->asDecimal($v3['week_avg_growth']);
                    echo '</td>';
                    echo '<td>';
                    echo   Yii::$app->formatter->asDecimal($v3['week_avg_rate']);
                    echo '</td>';
                    echo '</tr>';
                }
                echo '<tr><td colspan="4" style="background: #eee;height: 1px;"></td></tr>';
            }
            echo '<tr><td colspan="4" style="background: #ddd;height: 2px;"></td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

