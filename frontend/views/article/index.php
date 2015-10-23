<?php
    use yii\helpers\StringHelper;
    use yii\helpers\Url;
    $this->registerCss(
        <<<CSS
        dt{float: left;margin-right: 20px;width: 50px;}
CSS

    );
?>
<div class="row col-md-10">
    <div class="row">
        <h2>文章列表</h2>
    </div>
    <div class="row">
        <div class="col-md-6">
           <?php foreach($articles as $article): ?>
               <a href="<?= Url::to(['/article/detail','id'=>$article->id]) ?>">
                   <dl>
                       <dt>
                           <img src="<?= $article->cover_img ?>" width="50px">
                       </dt>
                       <dd>
                           <h3><?=  $article->title?></h3>
                           <span><?= $article->author ?></span>
                           <span><?= $article->view_count ?></span>
                           <span><?= $article->publish_at ?></span>
                       </dd>
                   </dl>
               </a>
            <?php endforeach;?>
        </div>
        <div class="col-md-4">
            <?php foreach($recommendArticles as $k=>$article): ?>
                <div class="row">
                    <a href="<?= Url::to(['/article/detail','id'=>$article->id]) ?>">
                        <div class="col-md-2">
                            <?= $k+1 ?>
                        </div>
                        <div class="col-md-8">
                            <?= $article->title ?>
                        </div>
                    </a>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>