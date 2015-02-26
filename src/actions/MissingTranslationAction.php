<?php

namespace yiidreamteam\i18n\actions;

use Yii;
use yii\base\Action;
use yii\bootstrap\Alert;
use yii\db\Exception;
use yiidreamteam\i18n\models\Message;

/**
 * Class MissingTranslationAction
 * @package yiidreamteam\i18n\actions
 */
class MissingTranslationAction extends Action
{

    public function run()
    {
        if (Yii::$app->request->isPost && Yii::$app->request->validateCsrfToken()) {
            try {
                $messages = Yii::$app->request->post('messages');
                foreach ($messages as $idLanguage => $message) {
                    if ($message) {
                        list($id, $language) = explode('::', $idLanguage);
                        Message::updateAll(
                            ['translation' => $message],
                            'id = :id AND language = :language',
                            [':id' => $id, ':language' => $language]
                        );
                    }
                }
                $class = 'success';
                $body = 'Cообщения успешно переведены!';
            } catch (Exception $e) {
                $class = 'danger';
                $body = 'Произошла ошибка!' . (YII_DEBUG ? ' код: <b>' . $e->getCode() . '</b>, имя: <b>' .
                        $e->getName() . '</b>, сообщение: <b>' . $e->getMessage() . '</b>' : '');
            } catch (\Exception $e) {
                $class = 'danger';
                $body = 'Произошла ошибка!' . (YII_DEBUG ? ' код: <b>' . $e->getCode() . '</b>, имя: <b>' .
                        $e->getName() . '</b>, сообщение: <b>' . $e->getMessage() . '</b>' : '');
            }
            return Alert::widget(['body' => $body, 'options' => ['class' => 'alert-' . $class]]);
        }
    }

}