<?php

namespace pantera\seo\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class SeoSearch extends Seo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 522],
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
        $query = Seo::find();
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
            Seo::tableName() . '.id' => $this->id,
        ])->andFilterWhere(['LIKE', Seo::tableName() . '.title', $this->title])
            ->andFilterWhere(['LIKE', Seo::tableName() . '.description', $this->description]);
        return $dataProvider;
    }
}
