<?php

namespace pantera\seo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pantera\seo\models\SeoPresets;

/**
 * SeoPresetsSearch represents the model behind the search form of `pantera\seo\models\SeoPresets`.
 */
class SeoPresetsSearch extends SeoPresets
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['key', 'comment', 'meta_title', 'meta_description', 'seo_h1', 'seo_text'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = SeoPresets::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'meta_title', $this->meta_title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'seo_h1', $this->seo_h1])
            ->andFilterWhere(['like', 'seo_text', $this->seo_text]);

        return $dataProvider;
    }
}
