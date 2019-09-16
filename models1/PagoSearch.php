<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pago;

/**
 * PagoSearch represents the model behind the search form of `app\models\Pago`.
 */
class PagoSearch extends Pago
{
    public $nombre;
    public $cedula;
    public $programa;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pago', 'numero_referencia', 'id_cliente', 'id_usuario'], 'integer'],
            [['monto'], 'number'],
            [['observaciones', 'tipo_pago','nombre','cedula','ref_payco_corto','factura','confirmacion','programa'], 'safe'],
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
        $query = Pago::find()->innerJoinWith('cliente', true)->innerJoin('programa','cliente.id_programa=programa.id_programa')->orderBy(['id_pago'=>SORT_DESC]);

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
            'id_pago' => $this->id_pago,
            'numero_referencia' => $this->numero_referencia,
            'monto' => $this->monto,
            'cliente.id_cliente' => $this->id_cliente,
            'id_usuario' => $this->id_usuario,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones])
        ->andFilterWhere(['like', 'nombre', $this->nombre])
        ->andFilterWhere(['like', 'cedula', $this->cedula])
        ->andFilterWhere(['like', 'tipo_pago', $this->tipo_pago])
        ->andFilterWhere(['like', 'factura', $this->factura])
        ->andFilterWhere(['like', 'confirmacion', $this->confirmacion])
        ->andFilterWhere(['like', 'nombre_programa', $this->programa])
        ->andFilterWhere(['like', 'ref_payco_corto', $this->ref_payco_corto]);
//var_dump($query);
        return $dataProvider;
    }
}
