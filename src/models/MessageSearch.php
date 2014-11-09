<?php

namespace yiidreamteam\i18n\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

class MessageSearch extends Message
{

    public $category;
    public $source;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'source', 'language', 'translation'], 'safe'],
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

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', 'translation', $this->translation])
            ->andFilterWhere(['like', 'sourceMessage.message', $this->source])
            ->andFilterWhere(['like', 'sourceMessage.category', $this->category]);

        return $dataProvider;
    }
}
