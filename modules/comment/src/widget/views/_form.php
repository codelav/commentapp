<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div id="comment-wrapper" class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 comments-input">

        <?php if(Yii::$app->user->isGuest): ?>

                <p>Only authenticated users can leave comments</p>

    <?php 
        $model = new \app\models\LoginForm();
        $form = ActiveForm::begin([
            'id' => 'login-form',
            'action' => '/site/auth',
            'options' => [
                'data-pjax' => ''
            ],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>
        <?= Html::hiddenInput('model', $commentModel->model) ?>
        <?= Html::hiddenInput('model_id', $commentModel->model_id) ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

        <?php else: ?>

            <?php $form = ActiveForm::begin([
                'action' => Url::to(['/comment/default/comment']),
                'validationUrl' => Url::to(['comment/default/validate']),
                'validateOnChange' => false,
                'validateOnBlur' => false,
                'options' => [
                    'id' => 'comment-form',
                    'data-pjax' => ''
                ]
            ]) ?>

            <div id="media" class="media">
                <div class="media-body">
                	<?= $form->field($commentModel, 'model')->hiddenInput()->label(false) ?>
                	<?= $form->field($commentModel, 'model_id')->hiddenInput()->label(false) ?>
                    <?= $form->field($commentModel, 'parent_id')->hiddenInput()->label(false) ?>
                    <?= $form->field($commentModel, 'content', ['template' => '{input}{error}'])->textarea(['placeholder' => Yii::t('comment', 'Write comment here...')]) ?>
                    <div class="media-buttons">
                        <div class="row nospace">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                <span id="comment-reply_to"></span>
                                <?= Html::button(
                                    Yii::t('comments', 'Cancel'), [
                                        'class' => 'btn btn-default btn-xs reply-cancel',
                                        'type' => 'button',
                                        'data' => [
                                            'action' => 'cancel-reply'
                                        ]
                                    ]
                                ) ?>
                                <?= Html::submitButton(Yii::t('comment', 'Post'), [
                                    'id' => 'submitButton',
                                    'class' => 'btn btn-primary btn-xs',
                                    'data' => [
                                        'action' => Url::to(['/comment/default/comment'])
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php $form->end(); ?>

        <?php endif; ?>

    </div>
</div>