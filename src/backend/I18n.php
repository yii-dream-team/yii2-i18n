<?php

namespace yiidreamteam\i18n\backend;

use Yii;
use yii\i18n\MissingTranslationEvent;
use yiidreamteam\i18n\models\SourceMessage;
use yii\base\Module;

class I18n extends Module
{

    const MISSING_TRANSLATIONS_KEY = 'missingTranslations';
    const EXISTING_TRANSLATIONS_KEY = 'existingTranslations';

    public $pageSize = 50;

    public $prefix;

    public $controllerNamespace = 'yiidreamteam\i18n\backend\controllers';

    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('yiidreamteam/i18n', $message, $params, $language);
    }

    /**
     * @param MissingTranslationEvent $event
     */
    public static function missingTranslation(MissingTranslationEvent $event)
    {
//        $sourceMessage = SourceMessage::find()
//            ->where('category = :category and message = binary :message', [
//                ':category' => $event->category,
//                ':message' => $event->message
//            ])
//            ->with('messages')
//            ->one();
//
//        if (!$sourceMessage) {
//            $sourceMessage = new SourceMessage;
//            $sourceMessage->setAttributes([
//                'category' => $event->category,
//                'message' => $event->message
//            ], false);
//            $sourceMessage->save(false);
//        }

//        $sourceMessage->initMessages();
//        $sourceMessage->saveMessages();

        if (($existingTranslations = Yii::$app->cache->get(self::EXISTING_TRANSLATIONS_KEY)) === false) {
            $existingTranslations = [];
        }
        if (!in_array(md5($event->category . $event->message), $existingTranslations)) {
            $missingTranslations = Yii::$app->cache->get(self::MISSING_TRANSLATIONS_KEY);
            $missingTranslations[] = [
                'category' => $event->category,
                'message' => $event->message
            ];
            Yii::$app->cache->set(self::MISSING_TRANSLATIONS_KEY, $missingTranslations);
        }
    }

}