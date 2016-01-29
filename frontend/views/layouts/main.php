<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script type="text/javascript">
        var month_detail_filter = '<?= Url::to(['/api/fund-detail-filter','date_type'=>'month']) ?>';//
        var week_detail_filter = '<?= Url::to(['/api/fund-detail-filter','date_type'=>'week']) ?>';
        var day_detail_filter = '<?= Url::to(['/api/fund-detail-filter','date_type'=>'day']) ?>';
        var fund_duplicate = '<?= Url::to(['/api/duplicate']) ?>';//获取筛选编号中重复的数据
        var get_week_filter = '<?= Url::to(['/api/get-week-filter']) ?>';//ajax处理周筛选
        var get_week_duplicate = '<?= Url::to(['/api/get-week-duplicate']) ?>';//ajax处理每周重复数据
        var fund_filter = '<?= Url::to(['/api/fund-filter']) ?>';//按照条件查询符合条件基金
    </script>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'yang',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => '首页', 'url' => ['/site/index']],
        ['label' => '关于', 'url' => ['/site/about']],
        ['label' => '联系我', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '注册', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => '账户设置', 'url' => ['/site/change']];
        $menuItems[] = ['label' => '个人中心', 'url' => ['/user/index']];
        $menuItems[] = [
            'label' => '退出 (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    $menuItems[] = ['label' => '条件筛选', 'url' => ['/fund']];
    $menuItems[] = ['label' => '指定日期删选', 'url' => ['/fund/sort']];
    $menuItems[] = ['label' => '周筛选', 'url' => ['/fund/week-filter']];
    $menuItems[] = ['label' => '月排名', 'url' => ['/fund/month-sort']];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
