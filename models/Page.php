<?php

namespace app\models;

use Yii;

class Page extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%pages}}';
    }

    public static function getMenuList()
    {
    	$pages = static::find()->all();

    	$list = [];
    	foreach($pages as $page) {
    		$list[] = ['label' => $page->title, 'url' => ['site/display/' . $page->id]];
    	}

    	return $list;
    }
}

