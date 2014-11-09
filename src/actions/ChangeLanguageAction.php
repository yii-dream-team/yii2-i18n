<?php

namespace yiidreamteam\i18n\actions;

use Yii;
use yii\base\Action;
use yiidreamteam\i18n\Module;

/**
 * Description of ChangeLanguageAction
 *
 * @author ValentinK <rlng-krsk@yandex.ru>
 */
class ChangeLanguageAction extends Action
{

    public function run()
    {
        Yii::$app->I18N->setLanguage();
        return $this->controller->goBack();
    }

}
