<?php
    use yii\helpers\Url;
    use yii\helpers\Html;

    $this->registerCss(
        <<<CSS
        .content-body{border: 1px solid #ccc;}
CSS
    );

$this->title = '采集文章管理';
$this->params['breadcrumbs'][] = ['label' => '文章管理', 'url' => ['/article/index']];
$this->params['breadcrumbs'][] = ['label' => '文章采集管理', 'url' => ['/article/gather']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a('修改文章', ['/article/update','id'=>$article->id], ['class' => 'btn btn-success']) ?>
</p>
<h2><?= $article->title.'[正文预览]' ?></h2>

<div class="row content-body">
   <?= $article->content ?>
</div>

