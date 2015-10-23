<?php
    use yii\helpers\Url;

    $this->registerCss(
        <<<CSS
    html{height: 100%;}
    body{height: 100%;}
    .wrap{height: 100%;}
    .container{height: 100%;}
    .frontend-article-detail{height: 90%;}
    .footer{height: 10%;}
CSS
    );
?>
<div class="container frontend-article-detail">
    <div class="row">
        <h2>
            <?= $article->title ?>
        </h2>
        <p>
            <span>
                <?= $article->publish_at ?> 作者：<?= $article->author ?>  阅读：<?=  $article->view_count?>
            </span>
        </p>
    </div>
    <div class="row">
        <img src="<?= $article->cover_img ?>" alt="<?= $article->title ?>">
       <?= $article->content ?>
    </div>

</div>

<div class="row footer">
    <?php if(count($tags)>0): ?>
        <?php foreach($tags as $tag): ?>
            <span>
                <?= $tag['name'].'['.$tag['article_count'].']' ?>
            </span>
        <?php endforeach;?>
    <?php endif; ?>
</div>
