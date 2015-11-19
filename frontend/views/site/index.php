<?php
    \frontend\assets\DraggabillyAsset::register($this);
    $this->registerJs(<<<JS
        var containment = $('.box');
        var draggable = $('.draggable').draggabilly({
            // options...
            handle: '.handle',
            containment: true
        })
JS
);
?>
<style type="text/css">
    .box{border: 3px solid #ff2222;height: 500px;}
</style>
<div class="box">
    <div style="width: 300px;height: 300px;background: #ccc;" class="draggable">
        <div class="handle" style="width: 100%; height: 20px;background: #777;text-align: center;cursor: move;">拖拽</div>
    </div>
</div>
<script>

</script>
