<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\comment\src\models\CommentRating;
?>

<div class="media-container">
    <div class="media-body">
        <div class="media-info">
            <h4 class="media-heading">
                <?= $model->getUserName() ?>
                <small><?= $model->getPostedDate() ?></small>
                <small id="score" style="float:right" class="score text-right">
                    <?= (isset($model->ratingAvg['rating'])?Yii::t('comment', 'Rating').': '.$model->ratingAvg['rating']:Yii::t('comment', 'Not rated')) ?>
                </small>
                <?php if($model->created_by != $widget->currentUser->getId() && !Yii::$app->user->isGuest && !CommentRating::hasVote($widget->currentUser->getId(), $model->id)): ?>
                <small>
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['/comment/default/rate']),
                        'options' => [
                            'id' => 'rate-form-' . $model->id,
                            'data-pjax' => ''
                        ]
                    ]) ?>
                    <?= Html::hiddenInput('comments_id', $model->id) ?>
                    <?= Html::dropDownList('rate', '0', [0=>'Rate',1=>1,2=>2,3=>3,4=>4,5=>5], ['onchange'=>'$(this).closest("form").submit();']) ?>
                    <?php $form->end(); ?>
                </small>
            <?php endif; ?>
            </h4>

            <?= Html::encode($model->content); ?>

            <div class="row nospace">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="comment-info">
                        <div class="comment-reply">
                            <a class="reply" href="#" data-name="<?= $model->getUserName() ?>" data-id="<?= $model->id ?>">Reply</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($widget->sortField == 'created_at'): ?>
    <?php $nested = $model->getNestedComments($childs); ?>
    <?php if(count($nested)): ?>
        <?php $paddingLevel++; ?>
        <?php foreach($nested as $comment): ?>
            <div class="media <?= ($paddingLevel <= $maxPaddingLevel?'padding-level':'') ?>">
                <?= $this->render('_comment', [
                    'model' => $comment,
                    'paddingLevel' => $paddingLevel,
                    'maxPaddingLevel' => $maxPaddingLevel,
                    'widget' => $widget,
                    'childs' => $childs,
                ]) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>