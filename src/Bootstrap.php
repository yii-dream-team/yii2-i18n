<?php

namespace yiidreamteam\i18n;

use yii\base\BootstrapInterface;
use Yii;
use yii\data\Pagination;
use yiidreamteam\i18n\commands\I18nCommand;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application && $i18nModule = Yii::$app->getModule('i18n')) {
            $moduleId = $i18nModule->id;
            $app->getUrlManager()->addRules([
                'translation/<id:\d+>' => $moduleId . '/default/update',
                'translation/mass-update' => $moduleId . '/default/mass-update',
                'translation' => $moduleId . '/default/index',
            ], false);

            Yii::$container->set(Pagination::className(), [
                'pageSizeLimit' => [1, 100],
                'defaultPageSize' => $i18nModule->pageSize
            ]);
        }

        if ($app instanceof \yii\console\Application) {
            if (!isset($app->controllerMap['i18n'])) {
                $app->controllerMap['i18n'] = I18nCommand::className();
            }
        }
    }
}
