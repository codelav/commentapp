<?php

namespace app\modules\comment\src;

use Yii;

class Module extends \yii\base\Module
{
    public $userModel;
    public $commentModelClass = 'app\modules\comment\src\models\Comment';
    public $commentRatingModelClass = 'app\modules\comment\src\models\CommentRating';

    public function init()
    {
        parent::init();

        if ($this->userModel === null) {
            $this->userModel = Yii::$app->getUser()->identityClass;
        }
    }
}