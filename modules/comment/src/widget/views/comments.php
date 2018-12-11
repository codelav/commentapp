<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

use codelav\comment\Module as CommentModule;

?>

<div id="comments-wrapper" class="comments">

    <div id="comments-fullComments">

        <?php Pjax::begin(['enablePushState' => false, 'clientOptions' => ['method' => 'POST']]); ?>

        <div id="comments-container-header" class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 comments-header">
				<h2 class="page-header"><?= Yii::t('comment', 'Comments') ?>
					<span class="text-right">
					<?php $form = ActiveForm::begin([
                        'action' => Url::to(['/comment/default/sort']),
                        'options' => [
                            'id' => 'sort-best',
                            'data-pjax' => '',
                            'class' => 'inline-form'
                        ]
                    ]) ?>
                    <?= Html::hiddenInput('type', 'best') ?>
                    <?= Html::hiddenInput('model', $widget->model) ?>
                    <?= Html::hiddenInput('model_id', $widget->model_id) ?>
                    <?= Html::submitButton(Yii::t('comment', 'Show the best'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <?php $form->end(); ?>
					
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['/comment/default/sort']),
                        'options' => [
                            'id' => 'sort-worst',
                            'data-pjax' => '',
                            'class' => 'inline-form'
                        ]
                    ]) ?>
                    <?= Html::hiddenInput('type', 'worst') ?>
                    <?= Html::hiddenInput('model', $widget->model) ?>
                    <?= Html::hiddenInput('model_id', $widget->model_id) ?>
                    <?= Html::submitButton(Yii::t('comment', 'Show the worst'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <?php $form->end(); ?>
				</span>	
				</h2>
				
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= ListView::widget(
                    array_merge(
                        [
                            'dataProvider' => $dataProvider,
                        ], $widget->getListViewConfig($childs)
                    )
                ) ?>
            </div>
        </div>
        <br>
        <?php
            echo $this->render($widget->formView, [
                'commentModel' => $commentModel,
                'widget' => $widget
            ]);
		?>

        <?php Pjax::end(); ?>

    </div>

</div>