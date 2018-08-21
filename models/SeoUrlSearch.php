<?php

namespace pantera\seo\models;

use yii\data\ActiveDataProvider;

class SeoUrlSearch extends SeoSearch
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['url'], 'string', 'max' => 255];
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
        $dataProvider->query->andWhere(['IS NOT', Seo::tableName() . '.url', null])
            ->andFilterWhere(['LIKE', Seo::tableName() . '.url', $this->url]);
        return $dataProvider;
    }
}
