<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Enlace;

/**
 * EnlaceSearch represents the model behind the search form of `app\models\Enlace`.
 */
class EnlaceSearch extends Enlace
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_enlace', 'id_cliente'], 'integer'],
            [['enlace'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Enlace::find();

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
            'id_enlace' => $this->id_enlace,
            'id_cliente' => $this->id_cliente,
        ]);

        $query->andFilterWhere(['like', 'enlace', $this->enlace]);

        return $dataProvider;
    }
}
