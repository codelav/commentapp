<?php

namespace codelav\comment;

use yii\base\BootstrapInterface;
use yii\base\Application;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
	public function bootstrap($app)
	{
		if ($app instanceof Application) {

			if (!isset($app->get('i18n')->translations['comment*'])) {
                $app->get('i18n')->translations['comment*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                    'sourceLanguage' => 'en'
                ];
            }
		}
	}
}
