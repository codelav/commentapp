<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h3>Congratulations!</h3>
        <p class="lead">You can check comment functionality <?=Html::a('here', "/site/display/1");?> or <?=Html::a('here', "/site/display/2");?>.</p>
    </div>
</div>
