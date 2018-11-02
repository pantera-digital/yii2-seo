<?php

namespace pantera\seo\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class SeoSlugSearch extends SeoSlug
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id'], 'integer'],
            [['slug', 'model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
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
        $query = SeoSlug::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            SeoSlug::tableName() . '.model_id' => $this->model_id,
        ])->andFilterWhere(['LIKE', SeoSlug::tableName() . '.model', $this->model])
            ->andFilterWhere(['LIKE', SeoSlug::tableName() . '.slug', $this->slug]);
        return $dataProvider;
    }
}
