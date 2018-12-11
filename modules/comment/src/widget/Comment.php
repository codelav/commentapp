<?php

namespace app\modules\comment\src\widget;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

use app\modules\comment\src\assets\CommentAsset;
use app\modules\comment\src\Module as CommentModule;

class Comment extends \yii\base\Widget
{
    public $commentsView = '@app/modules/comment/src/widget/views/comments';
    public $commentView = '@app/modules/comment/src/widget/views/_comment';
    public $formView = '@app/modules/comment/src/widget/views/_form';

    public $model;
    public $model_id;
    public $sortField = 'created_at';
    public $sortDirection = SORT_DESC;
    public $currentUser;
    public $paddingLevel = 1;
    public $maxPaddingLevel = 5;

    public $dataProviderConfig = null;
    public $listViewConfig = null;

    public function init()
    {
        parent::init();

        Yii::$app->getModule('comment');

        //have to use tablename instead of class name
        if ($this->model instanceof \yii\base\Model) {
            $this->model = $this->model::tableName();
        }

        $this->currentUser = Yii::$app->user;

        $this->registerAssets();
    }

    public function registerAssets()
    {
        $view = $this->getView();
        CommentAsset::register($view);
    }

    public function run()
    {
        $commentClass = CommentModule::getInstance()->commentModelClass;

        $params = [
            'model' => $this->model,
            'model_id' => $this->model_id,
            'all' => ($this->sortField == 'rating')
        ];

        $query = $commentClass::getComments($params);

        $dataProvider = new ActiveDataProvider(
            array_merge(['query' => $query], $this->getDataProviderConfig()));

        $commentsViewParams = [
            'dataProvider' => $dataProvider,
            'commentModel' => Yii::createObject($commentClass, [[
                'model' => $this->model,
                'model_id' => $this->model_id,
                'created_by' => Yii::$app->user->getId()
            ]]),
            'widget' => $this,
        ];

        $commentsViewParams['childs'] = [];
        if ($this->sortField == 'created_at') {
            $commentsViewParams['childs'] = $commentClass::getChildComments($params);
        }

        return $this->render($this->commentsView, $commentsViewParams);
    }

    public function getDataProviderConfig()
    {
        if ($this->dataProviderConfig === null) {
            $this->dataProviderConfig = [
                'key' => function ($model) {
                    return $model->id;
                },
                'pagination' => [
                    'pageSize' => 30
                ],
                'sort' => [
                    'attributes' => ['rating', 'created_at'],
                    'defaultOrder' => [
                        $this->sortField => $this->sortDirection
                    ]
                ]
            ];
        }

        return $this->dataProviderConfig;
    }

    public function getListViewConfig($childs)
    {
        if ($this->listViewConfig === null) {
            $this->listViewConfig = [
                'layout' => '{items}<div class="text-center">{pager}</div>',
                'options' => ['class' => 'comment-list'],
                'itemOptions' => ['class' => 'media'],
                'itemView' => function ($model, $key, $index, $widget) use($childs) {
                    return $this->render($this->commentView, [
                        'maxPaddingLevel' => $this->maxPaddingLevel,
                        'paddingLevel' => 1,
                        'widget' => $this,
                        'model' => $model,
                        'childs' => $childs,
                        'printTree' => ($this->sortField == 'created_at'),
                    ]);
                },
                'emptyText' => '',
                'pager' => [
                    'class' => \yii\widgets\LinkPager::className(),
                    'options' => ['class' => 'pagination pagination-sm'],
                    'maxButtonCount' => 5
                ]
            ];
        }

        return $this->listViewConfig;
    }
}
