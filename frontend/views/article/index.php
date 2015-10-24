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
         .article-attribute span{margin-left: 3%;}
         .media-right-title{
                border-radius: 4px;
                box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.1);
                font-size: 12px;
                line-height: 22px;
                background: #DDD none repeat scroll 0% 0%;
                width: 45px;
                display: block;
                height: 22px;
                text-align: center;
                color: #999;
                text-decoration: none;
         }
          .media-right-body{
              font-size: 14px;
                font-weight: 500;
                background: #EEE none repeat scroll 0% 0%;
                line-height: 26px;
                font-style: normal;
                text-align: center;
          }
          .article-recommend{
            padding: 0px;
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
                               <div class="media-action article-attribute">
                                   <span>发布时间：<?= $article->publish_at ?></span>
                                   <span>作者：<?= $article->author?></span>
                               </div>
                            </div>
                            <div class="media-right">
                                <div class="media-right-title">浏览</div>
                                <div class="media-right-body">
                                    <span><?= $article->view_count ?></span>
                                </div>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">推荐文章</h2>
            </div>
            <div class="panel-body">
                <ul class="media-lis article-recommend">
                    <?php foreach($recommendArticles as $k=>$article): ?>
                        <li class="media">
                            <div class="media-left">
                                <?= $k+1 ?>
                            </div>
                            <div class="media-body">
                                <a href="<?= Url::to(['/article/detail','id'=>$article->id]) ?>"><?= $article->title ?> </a>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>

</div>