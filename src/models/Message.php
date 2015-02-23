<?php

namespace yiidreamteam\i18n\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yiidreamteam\i18n\backend\I18n;

class Message extends ActiveRecord
{
    /**
     * @return string
     * @throws InvalidConfigException
     */
    public static function tableName()
    {
        $i18n = Yii::$app->getI18n();
        if (!isset($i18n->messageTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        return $i18n->messageTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language'], 'required'],
            ['id', 'integer'],
            ['language', 'string', 'max' => 16],
            ['translation', 'string'],
            ['translation', 'filter', 'filter' => 'trim']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => I18n::t('ID'),
            'language' => I18n::t('Language'),
            'translation' => I18n::t('Translation')
        ];
    }

    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }

}
