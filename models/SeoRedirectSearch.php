<?php

namespace pantera\seo\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class SeoRedirectSearch extends SeoRedirect
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['from', 'to'], 'string', 'max' => 255],
            [['code'], 'integer', 'max' => 3],
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
        $query = SeoRedirect::find();
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
            SeoRedirect::tableName() . '.id' => $this->id,
            SeoRedirect::tableName() . '.code' => $this->code,
        ])->andFilterWhere(['LIKE', SeoRedirect::tableName() . '.from', $this->from])
            ->andFilterWhere(['LIKE', SeoRedirect::tableName() . '.to', $this->to]);
        return $dataProvider;
    }
}
