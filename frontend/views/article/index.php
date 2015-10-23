<?php
    use yii\helpers\StringHelper;
    use yii\helpers\Url;
    $this->registerCss(
        <<<CSS
        .article-title{font-size: 18px;margin: 0px;}
         .panel .panel-heading {
            background: #FCFCFC;
            border-bottom: #eee solid 1px;
         }
CSS

    );
?>

<div class="row">
    <div class="col-md-9 col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">文章列表</h2>
            </div>
            <div class="panel-body">
                <ul class="media-list">
                    <?php foreach($articles as $article): ?>
                        <li class="media">
                            <div class="media-body">
                                <div class="media-heading">
                                    <h3 class="article-title">
                                        <span class="glyphicon glyphicon-list-alt"></span>
                                        <a href="<?= Url::to(['/article/detail','id'=>$article->id]) ?>">
                                            <?=  $article->title?>
                                        </a>
                                    </h3>
                                </div>
                               <div class="media-action">
                                   <span><?= $article->publish_at ?></span>
                               </div>
                            </div>
                            <div class="media-right">
                                <span><?= $article->view_count ?></span>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-lg-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="feature">
                    <?php foreach($recommendArticles as $k=>$article): ?>
                        <div class="span2">
                            <?= $k+1 ?>
                        </div>
                        <div class="span10">
                            <a href="<?= Url::to(['/article/detail','id'=>$article->id]) ?>"><?= $article->title ?> </a>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>

</div>