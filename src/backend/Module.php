<?php

namespace yiidreamteam\i18n\backend;

use Yii;
use yii\base\Module as YiiModule;

class Module extends YiiModule
{

    public $pageSize = 50;

    public $prefix;

    public $controllerNamespace = 'yiidreamteam\i18n\backend\controllers';

    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('yiidreamteam/i18n', $message, $params, $language);
    }

}