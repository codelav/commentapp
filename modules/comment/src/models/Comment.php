<?php

namespace app\modules\comment\src\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

use app\modules\comment\src\Module as CommentModule;

class Comment extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%comments}}';
    }

    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ]
            ],
        ];
    }

    public function rules()
    {
        return [
            [['content', 'model', 'model_id'], 'required'],
            [['parent_id'], 'exist', 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            [['model_id', 'created_by'], 'integer'],
            [['model'], 'string', 'max' => 255],
            [['content'], 'string', 'max' => 500],
            [['content'], 'filter', 'filter' => 'yii\helpers\HtmlPurifier::process'],
        ];
    }

    public static function getComments($params)
    {
        $commentTable = static::tableName();
        $commentRatingTable = CommentRating::tableName();

        $where = [
            'model' => $params['model'],
            'model_id' => $params['model_id'],
        ];

        if (!isset($params['all']) || $params['all'] !== true) {
            $where['parent_id'] = null;
        }

        $query = static::find()
            ->select([new \yii\db\Expression("ROUND(COALESCE(AVG($commentRatingTable.rate), 0), 2) as rating"), 
                    $commentTable . '.id',
                    $commentTable . '.created_by',
                    $commentTable . '.content',
                    $commentTable . '.created_at',
                    $commentTable . '.parent_id',
                ])
            ->leftJoin($commentRatingTable, "$commentRatingTable.comments_id = $commentTable.id")
            ->where($where);

        $query->groupBy($commentTable . '.id');

        return $query;
    }

    public static function getChildComments($params)
    {
        $commentTable = static::tableName();
        $commentRatingTable = CommentRating::tableName();

        $childs = static::find()
            ->select([new \yii\db\Expression("ROUND(COALESCE(AVG($commentRatingTable.rate), 0), 2) as rating"), 
                    $commentTable . '.id',
                    $commentTable . '.created_by',
                    $commentTable . '.content',
                    $commentTable . '.created_at',
                    $commentTable . '.parent_id',
                ])
            ->leftJoin($commentRatingTable, "$commentRatingTable.comments_id = $commentTable.id")
            ->where(['model' => $params['model'], 'model_id' => $params['model_id']])
            ->andWhere(['>', 'parent_id', 0])
            ->groupBy($commentTable . '.id')->all();

        return $childs;
    }

    public function getNestedComments($childs)
    {
        $rootCommentId = $this->id;
        $nested = array_filter($childs, function($childComment) use ($rootCommentId) {
                return isset($childComment->parent_id) && $childComment->parent_id == $rootCommentId;
            });

        return $nested;
    }

    public function getUser()
    {
        return $this->hasOne(CommentModule::getInstance()->userModel, ['id' => 'created_by']);
    }

    public function getUserName()
    {
        if ($this->user !== null) {
            return $this->user->username;
        }
        return 'Anonimous';
    }

    public function getRating()
    {
        return $this->hasMany(CommentModule::getInstance()->commentRatingModelClass, ['comments_id' => 'id']);
    }

    public function getRatingAvg()
    {
        return $this->getRating()
            ->select(['comments_id', 'rating' => 'ROUND(COALESCE(AVG(comment_ratings.rate),0), 2)'])
            ->groupBy('comments_id')->asArray(true)->one();
    }

    public function getPostedDate()
    {
        return Yii::$app->formatter->asRelativeTime($this->created_at);
    }
}
