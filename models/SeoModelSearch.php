<?php

namespace pantera\seo\models;

use yii\data\ActiveDataProvider;

class SeoModelSearch extends SeoSearch
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['item_id'], 'integer'];
        $rules[] = [['modelName'], 'string', 'max' => 255];
        return $rules;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $dataProvider = parent::search($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $dataProvider->query->andWhere(['IS', Seo::tableName() . '.url', null])
            ->andFilterWhere([
                Seo::tableName() . '.modelName' => $this->modelName,
                Seo::tableName() . '.item_id' => $this->item_id,
            ]);
        return $dataProvider;
    }
}
