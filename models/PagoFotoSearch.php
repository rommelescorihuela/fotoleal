<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PagoFoto;

/**
 * PagoFotoSearch represents the model behind the search form of `app\models\PagoFoto`.
 */
class PagoFotoSearch extends PagoFoto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pago_foto', 'numero_referencia', 'id_cliente', 'id_usuario'], 'integer'],
            [['monto'], 'number'],
            [['observaciones', 'tipo_pago', 'fecha_pago', 'paquete', 'ref_payco', 'confirmacion', 'factura', 'ref_payco_corto'], 'safe'],
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
        $query = PagoFoto::find();

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
            'id_pago_foto' => $this->id_pago_foto,
            'numero_referencia' => $this->numero_referencia,
            'monto' => $this->monto,
            'id_cliente' => $this->id_cliente,
            'id_usuario' => $this->id_usuario,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'tipo_pago', $this->tipo_pago])
            ->andFilterWhere(['like', 'fecha_pago', $this->fecha_pago])
            ->andFilterWhere(['like', 'paquete', $this->paquete])
            ->andFilterWhere(['like', 'ref_payco', $this->ref_payco])
            ->andFilterWhere(['like', 'confirmacion', $this->confirmacion])
            ->andFilterWhere(['like', 'factura', $this->factura])
            ->andFilterWhere(['like', 'ref_payco_corto', $this->ref_payco_corto]);

        return $dataProvider;
    }
}
