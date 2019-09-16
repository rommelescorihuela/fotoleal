<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FotoAdicional;

/**
 * FotoAdicionalSearch represents the model behind the search form of `app\models\FotoAdicional`.
 */
class FotoAdicionalSearch extends FotoAdicional
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_foto', 'cantidad_foto', 'monto', 'total', 'id_usuario', 'id_cliente'], 'integer'],
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
        $query = FotoAdicional::find();

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
            'id_foto' => $this->id_foto,
            'cantidad_foto' => $this->cantidad_foto,
            'monto' => $this->monto,
            'total' => $this->total,
            'id_usuario' => $this->id_usuario,
            'id_cliente' => $this->id_cliente,
        ]);

        return $dataProvider;
    }
}
