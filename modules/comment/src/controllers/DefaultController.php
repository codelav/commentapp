<?php

namespace app\modules\comment\src\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;

use app\modules\comment\src\models\Comment;
use app\modules\comment\src\models\CommentRating;
use app\modules\comment\src\widget\Comment as CommentWidget;

class DefaultController extends \yii\web\Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['comment', 'rate', 'sort'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['comment', 'rate', 'sort'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['sort'],
                        'roles' => ['?'],
                    ],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'only' => ['comment', 'rate', 'sort'],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'comment' => ['post'],
                    'rate' => ['post'],
                    'sort' => ['post'],
                ],
            ],
        ];
    }

    public function actionSort()
    {
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            $widgetParams = [
                'sortField' => 'rating',
                'sortDirection' => (Yii::$app->request->post('type') == 'best' ? SORT_DESC : SORT_ASC),
                'model' => Yii::$app->request->post('model'), 
                'model_id' => Yii::$app->request->post('model_id'),
                
            ];

            return CommentWidget::widget($widgetParams);
        }
    }

    public function actionComment()
    {

    	if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {

    		$comment = new Comment();

    		if (!$comment->load(Yii::$app->request->post()) || !$comment->validate()) {
    			return [
                    'status' => 'error',
                    'errors' => $comment->errors
                ];
            }

            if ($comment->save()) {
                return CommentWidget::widget(['model' => $comment->model, 'model_id' => $comment->model_id]);
            }
    	}
    }

    public function actionRate()
    {
    	if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {

            $comment = Comment::find()->where(['id' => Yii::$app->request->post('comments_id')])->one();
            if (!$comment) {
                return [
                    'status' => 'error',
                    'errors' => 'Comment not found'
                ];
            }

            if ($comment->created_by == Yii::$app->user->getId()) {
                return [
                    'status' => 'error',
                    'errors' => 'Comment owner can\'t vote'
                ];
            }

    		$commentRating = new CommentRating();

            $commentRating->comments_id = Yii::$app->request->post('comments_id');
            $commentRating->rate = Yii::$app->request->post('rate');

    		if (!$commentRating->validate()) {
    			return [
                    'status' => 'error',
                    'errors' => $commentRating->errors
                ];
            }

            if ($commentRating->save()) {
                return CommentWidget::widget(['model' => $commentRating->comment->model, 'model_id' => $commentRating->comment->model_id]);
            }
    	}

    	return [
            'status' => 'error',
            'message' => Yii::t('comment', 'Sorry, service unavailable at the moment. Please try again later.')
        ];
    }
}