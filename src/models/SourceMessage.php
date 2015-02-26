<?php

namespace yiidreamteam\i18n\models;

use yii\base\InvalidConfigException;
use Yii;
use yii\db\ActiveRecord;
use yiidreamteam\i18n\models\SourceMessageQuery;
use yiidreamteam\i18n\backend\Module;

class SourceMessage extends ActiveRecord
{
    /**
     * @return string
     * @throws InvalidConfigException
     */
    public static function tableName()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->sourceMessageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        return $i18n->sourceMessageTable;
    }

    public static function find()
    {
        return new SourceMessageQuery(static::className());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['message', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('ID'),
            'category' => Module::t('Category'),
            'message' => Module::t('Message'),
            'status' => Module::t('Translation status')
        ];
    }

    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id'])->indexBy('language');
    }

    /**
     * @return array|SourceMessage[]
     */
    public static function getCategories()
    {
        return SourceMessage::find()->select('category')->distinct('category')->asArray()->all();
    }

    public function initMessages()
    {
        $messages = [];
        foreach (Yii::$app->getI18n()->languages as $code => $name) {
            if (!isset($this->messages[$code])) {
                $message = new Message;
                $message->language = $code;
                $messages[$code] = $message;
            } else {
                $messages[$code] = $this->messages[$code];
            }
        }
        $this->populateRelation('messages', $messages);
    }

    public function saveMessages()
    {
        $messages = [];
        /** @var Message $message */
        foreach ($this->messages as $message) {
            $this->link('messages', $message);
            $message->save();
            $messages[] = [
                'id' => $message->id,
                'translation' => $message->translation,
                'language' => $message->language,
            ];
        }
        return $messages;
    }
}
