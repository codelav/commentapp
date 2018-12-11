<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

use app\modules\comment\src\widget\Comment;

$this->title = $model->title;
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="site-about">
    <h1><?= Html::encode($model->title) ?></h1>
    <p>
        <?=$model->body; ?>
    </p>

    <?php echo Comment::widget(['model' => $model, 'model_id' => $model->id, 'sortField' => 'created_at', 'sortDirection' => SORT_DESC]); ?>

</div>
