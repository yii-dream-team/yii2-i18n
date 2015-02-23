<?php

namespace yiidreamteam\i18n\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\i18n\DbMessageSource;
use yii\i18n\MissingTranslationEvent;
use yiidreamteam\i18n\models\SourceMessage;

class I18N extends \yii\i18n\I18N
{

    /** @var string */
    public $sourceMessageTable = '{{%source_message}}';
    
    /** @var string */
    public $messageTable = '{{%message}}';
    
    /** 
     * Array of supported languages in format:
     * en-EN => English
     * @var array
     */
    public $languages;
    
    /** @var array */
    public $missingTranslationHandler = ['\yiidreamteam\i18n\components\I18N', 'missingTranslation'];

    /** @var string */
    public $autoSetLanguage = true;

    /** @var string $defaultLanguage a default user language, default to getLanguage() method */
    public $defaultLanguage;

    /** @var string */
    public $languageSessionKey;

    /** @var string */  
    public $languageParam;

    /** @var string $_language a reference to the language set */
    private $_language;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        
        if (!$this->languages)
            throw new InvalidConfigException('You should configure i18n component [language]');

        if (empty($this->defaultLanguage))
            $this->defaultLanguage = Yii::$app->language;

        if(empty($this->languageSessionKey))
            $this->languageSessionKey = 'language';
        
        if(empty($this->languageParam))
            $this->languageParam = 'language';

        if (!isset($this->translations['*'])) {
            $this->translations['*'] = [
                'class' => DbMessageSource::className(),
                'sourceMessageTable' => $this->sourceMessageTable,
                'messageTable' => $this->messageTable,
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }

        if (!isset($this->translations['app']) && !isset($this->translations['app*'])) {
            $this->translations['app'] = [
                'class' => DbMessageSource::className(),
                'sourceMessageTable' => $this->sourceMessageTable,
                'messageTable' => $this->messageTable,
                'on missingTranslation' => $this->missingTranslationHandler
            ];
        }

        if($this->autoSetLanguage)
            $this->setLanguage();
        else
            \Yii::$app->language = \Yii::$app->session->get($this->languageSessionKey, \Yii::$app->language);

        parent::init();
    }

    /**
     * @return array|mixed|string
     */
    public function getLanguage()
    {
        if ($this->_language !== null)
            return $this->_language;

        elseif (Yii::$app->has('session') && Yii::$app->session->has($this->languageSessionKey))
            $language = Yii::$app->session->get($this->languageSessionKey);
        elseif (Yii::$app->has('request') && Yii::$app->request->post($this->languageParam))
            $language = Yii::$app->request->post($this->languageParam);
        elseif (Yii::$app->has('request') && Yii::$app->request->get($this->languageParam))
            $language = Yii::$app->request->get($this->languageParam);
        else
            $language = Yii::$app->request->getPreferredLanguage(array_keys($this->languages));

        if (!array_key_exists($language, $this->languages))
        {
            if ($language === Yii::$app->sourceLanguage)
                $language = $this->defaultLanguage;
            elseif (strpos($language, "_") !== false)
            {
                $language = substr($language, 0, 2);
                if (!array_key_exists($language, $this->languages))
                    $language = $this->defaultLanguage;
            }
        }

        $this->_language = $language;
        return $language;
    }

    /**
     * @param null $language
     * @return array|mixed|null|string
     */
    public function setLanguage($language = null)
    {
        if ($language === null)
            $language = $this->getLanguage();
        
        $this->_language = $language;

        if(Yii::$app->has('session'))
            Yii::$app->session->set($this->languageSessionKey, $language);

        Yii::$app->language = $language;
        return $language;
    }

    /**
     * @param MissingTranslationEvent $event
     */
    public static function missingTranslation(MissingTranslationEvent $event)
    {
        $sourceMessage = SourceMessage::find()
            ->where('category = :category and message = binary :message', [
                ':category' => $event->category,
                ':message' => $event->message
            ])
            ->with('messages')
            ->one();

        if (!$sourceMessage) {
            $sourceMessage = new SourceMessage;
            $sourceMessage->setAttributes([
                'category' => $event->category,
                'message' => $event->message
            ], false);
            $sourceMessage->save(false);
        }

        $sourceMessage->initMessages();
        $sourceMessage->saveMessages();
    }

}
