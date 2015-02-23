<?php

namespace yiidreamteam\i18n\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

class MessageSearch extends Message
{

    const MESSAGE_TRANSLATED_YES = 'yes';
    const MESSAGE_TRANSLATED_NO  = 'no';

    public $category;
    public $source;
    public $translationStatus;
    public $prefix;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'source', 'language', 'translation', 'translationStatus'], 'safe'],
            ['prefix', 'in', 'range' => ['cabinet', 'shop', 'brand', 'bonus']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function search($params)
    {
        $query = Message::find()->joinWith(['sourceMessage' => function($query) {
            $query->from(['sourceMessage' => SourceMessage::tableName()]);
        }]);

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if($this->language)
            $query->filterWhere(['language' => $this->language]);

        if ($this->prefix) {
            $query->andFilterWhere([
                'or',
                ['like', 'sourceMessage.category', $this->prefix . '.' . $this->category],
                ['like', 'sourceMessage.category', $this->prefix . '/' . $this->category],
            ]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', 'translation', $this->translation])
            ->andFilterWhere(['like', 'sourceMessage.message', $this->source]);

        if ($this->category) {
            if ($this->prefix) {
                $query->andFilterWhere([
                    'or',
                    ['like', 'sourceMessage.category', $this->prefix . '.' . $this->category],
                    ['like', 'sourceMessage.category', $this->prefix . '/' . $this->category],
                ]);
            } else {
                $query->andFilterWhere(['like', 'sourceMessage.category', $this->category]);
            }
        }

        if($this->translationStatus === static::MESSAGE_TRANSLATED_YES)
            $query->andWhere('translation IS NOT NULL AND translation <> ""');
        elseif($this->translationStatus === static::MESSAGE_TRANSLATED_NO)
            $query->andWhere('translation IS NULL OR translation = ""');

        return $dataProvider;
    }
}
