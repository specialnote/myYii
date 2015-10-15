<?php
    use yii\helpers\StringHelper;
    use yii\helpers\Url;
?>
<div class="row col-md-10">
    <div class="row">
        <h2>文章列表</h2>
    </div>
    <div class="row">
        <div class="col-md-6">
           <?php foreach($articles as $article): ?>
               <div class="row">
                   <div class="col-md-4">
                        <img src="<?= $article->cover_img ?>">
                   </div>
                   <div class="col-md-6">
                        <h3><?=  $article->title?></h3>
                       <span><?= $article->author ?></span>
                       <span><?= $article->view_count ?></span>
                       <span><?= $article->publish_at ?></span>
                   </div>
               </div>
            <?php endforeach;?>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>