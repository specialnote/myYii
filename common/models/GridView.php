<?php
namespace common\models;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/24 0024
 * Time: 上午 10:03
 */

class GridView extends \yii\grid\GridView
{
    public $layout = '{items}
    <div class="row">
        <div class="col-xs-6">
            <div class="dataTables_info" id="dynamic-table_info" role="status" aria-live="polite">
            {summary}
            </div>
        </div>
        <div class="col-xs-6">
            <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">{pager}</div>
        </div>
    </div>
    ';

    public $pager = [
        'options' => [
            'class' => 'pagination'
        ]
    ];

    public $tableOptions = [
        'class' => 'table table-striped table-bordered table-hover'
    ];

    public $summary = '{begin}-{end}条，共{totalCount}条数据，第{page}页,共{pageCount}页';

}