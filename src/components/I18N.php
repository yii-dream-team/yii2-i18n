<?php

namespace yiidreamteam\i18n\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\i18n\DbMessageSource;

class I18N extends \yii\i18n\I18N
{
    /** @var string */
    public $sourceMessageTable = '{{%source_message}}';
    
    /** @var string */
    public $messageTable = '{{%message}}';
    
    /** @var array */
    public $languages;
    
    /** @var array */
    public $missingTranslationHandler = ['yiidreamteam\i18n\Module', 'missingTranslation'];

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

        parent::init();
    }

    public function getLanguage()
    {
        if ($this->_language !== null)
            return $this->_language;
        elseif (Yii::$app->session->has($this->languageSessionKey))
            $language = Yii::$app->session->get($this->languageSessionKey);
        elseif (Yii::$app->request->post($this->languageParam))
            $language = Yii::$app->request->post($this->languageParam);
        elseif (Yii::$app->request->get($this->languageParam))
            $language = Yii::$app->request->get($this->languageParam);
        else
            $language = Yii::$app->request->getPreferredLanguage();

        if (!key_exists($language, $this->languages))
        {
            if ($language === Yii::$app->sourceLanguage)
                $language = $this->defaultLanguage;
            elseif (strpos($language, "_") !== false)
            {
                $language = substr($language, 0, 2);
                if (!key_exists($language, $this->languages))
                    $language = $this->defaultLanguage;
            }
        }

        $this->_language = $language;
        return $language;
    }

    public function setLanguage($language = null)
    {
        if ($language === null)
            $language = $this->getLanguage();
        
        $this->_language = $language;
        Yii::$app->session->set($this->languageSessionKey, $language);
        Yii::$app->setLanguage($language);
        return $language;
    }
}