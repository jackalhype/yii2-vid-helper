<?php
?>
<h1>Новое видео</h1>
<div class="form-controls">
    <form>
        <input type="hidden" name="node_id" value="" />
        <div class="row">
            <button type="button" class="btn btn-success" id="save-btn">Сохранить</button>
            <button type="button" class="btn btn-default" id="submit-tree-btn">Обновить</button>
        </div>
        <br>
        <div class="row">
            <textarea name="tree" class="form-control" autocomplete="off"></textarea>
        </div>
    </form>
</div>

<template id="node-tpl">
    <div class="node-wrap">
        <div class="row">
            <div class="md-col-9" >
                <input type="text" name="title" value="" autocomplete="off" />
            </div>
            <div class="md-col-1" >
                <input type="checkbox" name="status" value="" />
            </div>
        </div>
    </div>
</template>
