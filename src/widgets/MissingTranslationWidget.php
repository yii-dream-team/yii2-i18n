<?php 

namespace yiidreamteam\i18n\widgets;

use Yii;
use yii\base\Widget;
use yiidreamteam\i18n\components\I18N;

class MissingTranslationWidget extends Widget
{

	/** @var $viewFile you can specify your own view file */
	public $viewFile = 'missingTranslationWidget';

	/** @var $selectedLanguage by default */
	public $selectedLanguage;

    /** @var $accessRole role to widget access */
    public $accessRole;

    /** @var $missingTranslations */
    public $missingTranslations;

	public function init()
	{
		if (!$this->selectedLanguage) {
            $this->selectedLanguage = Yii::$app->i18n->getLanguage();
        }
	}

	public function run()
	{
        if ($this->existMissingTranslations()) {
            return $this->render($this->viewFile, [
                'selectedLanguage' => $this->selectedLanguage,
                'missingTranslations' => $this->missingTranslations,
            ]);
        }
	}

    public function setMissingTranslations()
    {
        $this->missingTranslations = Yii::$app->cache->get(I18N::MISSING_TRANSLATIONS_KEY);
        Yii::$app->cache->delete(I18N::MISSING_TRANSLATIONS_KEY);
        return $this->missingTranslations;
    }

    private function existMissingTranslations()
    {
//        return $this->setMissingTranslations() && Yii::$app->user->can($this->accessRole);
        return $this->setMissingTranslations();
    }

}