<?php
    use yii\helpers\StringHelper;
    use yii\helpers\Url;
    $this->registerCssFile('@web/css/article.css');
?>
<div class="row">
    <ul class="category-menu">
        <li class="<?= (empty($category_id))?'menu_selected':'' ?>">
            <a href="<?= Url::to(['/article/index']) ?>">全部</a>
        </li>
        <?php foreach($categories as $category): ?>
            <?php if($category->name): ?>
                <li class="<?= ($category_id==$category->id)?'menu_selected':'' ?>">
                    <a href="<?= Url::to(['/article/index','category_id'=>$category->id]) ?>">
                        <?= $category->name ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach;?>
    </ul>
</div>
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
                                        <a target="_blank" href="<?= Url::to(['/article/detail','id'=>$article->id]) ?>">
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
                            <div class="media-body recommend-title">
                                <span><?= $k+1 ?></span>
                                <a target="_blank" href="<?= Url::to(['/article/detail','id'=>$article->id]) ?>"><?= $article->title ?> </a>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">热门标签</h2>
            </div>
            <div class="panel-body">
                <ul class="media-lis article-tag">
                    <?php foreach($tags as $tag): ?>
                        <li class="<?= ($tag_id == $tag->id)?'tag_selected':'' ?>">
                            <a href="<?= Url::to(['/article/index','tag_id'=>$tag->id]) ?>">
                                <?= $tag->id.' '.$tag->name.'<font style="color:#555">('.$tag->article_count.')</font>' ?>
                            </a>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
    </div>

</div>