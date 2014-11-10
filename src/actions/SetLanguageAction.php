<?php

namespace yiidreamteam\i18n\actions;

use Yii;
use yii\base\Action;
use yiidreamteam\i18n\Module;
use yii\base\InvalidParamException;

/**
 * Description of SetLanguageAction
 *
 * @author ValentinK <rlng-krsk@yandex.ru>
 */
class SetLanguageAction extends Action
{

    public function run()
    {
        $language = Yii::$app->request->post(Yii::$app->i18n->languageParam, false) 
                 ?: Yii::$app->request->get(Yii::$app->i18n->languageParam, false);

        if(!array_key_exists($language, Yii::$app->i18n->languages))
            throw new InvalidParamException(Yii::t("app", "Invalid language param"));
            
        Yii::$app->i18n->setLanguage($language);
        return $this->controller->goBack();
    }

}