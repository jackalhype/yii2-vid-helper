<?php
    $this->registerJsFile('@web/js/main/video.js', ['depends' => 'backend\assets\AppAsset']);
?>

<h1>Новое видео</h1>
<div class="form-controls">
    <form id="video-form">
        <input type="hidden" name="node_id" id="node_id" value="" />
        <div class="row">
            <button type="button" class="btn btn-success" id="save-btn">Сохранить</button>
            <button type="reset" class="btn btn-default" id="refresh-btn">Очистить</button>
            <button type="reset" class="btn btn-warning" id="delete-btn">Удалить</button>
        </div>
        <br>
        <div class="row">
            <textarea id="tree-editor" name="tree" class="form-control" autocomplete="off"></textarea>
            <!--<textarea class="ckeditor" cols="120" id="tree-editor" name="tree" rows="15"></textarea>-->
        </div>
    </form>
</div>

<style>
    .cke-btn {
        padding: 5px;
    }
    .cke-btn:hover {
        background-color: #c7c7c7;
    }
</style>