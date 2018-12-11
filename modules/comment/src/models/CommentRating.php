<?php

namespace app\modules\comment\src\models;

use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

class CommentRating extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%comment_ratings}}';
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
            [['comments_id', 'rate'], 'required'],
            [['comments_id'], 'integer'],
            [['rate'], 'integer', 'min' => 1, 'max' => 5],
            [['comments_id'], 'exist', 'targetClass' => Comment::className(), 'targetAttribute' => 'id'],
        ];
    }

    public function getComment()
    {
        return $this->hasOne(Comment::className(), ['id' => 'comments_id']);
    }

    public static function hasVote($userId, $commentId)
    {
        return (bool) static::find()->select('id')->where(['comments_id' => $commentId, 'created_by' => $userId])->one();
    }
}
