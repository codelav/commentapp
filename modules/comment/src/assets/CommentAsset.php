<?php

namespace app\modules\comment\src\assets;

class CommentAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/comment/src/assets/sources/';
    public $css = [
        'css/comment.css',
    ];
    public $js = [
        'js/comment.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
