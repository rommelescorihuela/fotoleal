<?php

namespace app\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cliente;
use app\models\Pago;
use app\models\Direcion;
use yii\data\SqlDataProvider;

/**
 * ClienteSearch represents the model behind the search form of `app\models\Cliente`.
 */
class ClienteSearch extends Cliente
{
    public $nombre_programa;
    public $nombre_evento;
    public $estatus;
    public $paquete;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'cedula', 'telefono', 'celular', 'id_direccion', 'id_programa'], 'integer'],
            [['nombre', 'apellido', 'correo'], 'safe'],
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

        if(isset($_GET['Evento'])){

            $id_evento=$_GET['Evento']['id_evento'];
        }
        else {
        $id_evento=null;
        }
        if(isset($_GET['Cliente'])){
            if($_GET['Cliente']['id_programa']!=0)
            {
              $this->id_programa=$_GET['Cliente']['id_programa'];
            }
            else {
              $this->id_programa=null;
            }
        }
        if(isset($_GET['ClienteSearch'])){
            if($_GET['ClienteSearch']['paquete']!=null)
            {
              $this->paquete=$_GET['ClienteSearch']['paquete'];
            }
            else {
              $this->paquete=null;
            }
        }
        $query = Cliente::find();
        $query->innerJoin("pago", "cliente.id_cliente=pago.id_cliente");
        $query->innerJoin("direccion", "cliente.id_direccion=direccion.id_direccion");
        $query->innerjoin("programa","programa.id_programa=cliente.id_programa");
        $query->innerjoin("evento","evento.id_evento=programa.id_evento");
        //$query->with("pagos");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 100 ],
        ]);
        //var_dump($query);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails

            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_cliente' => $this->id_cliente,
            'cedula' => $this->cedula,
            'telefono' => $this->telefono,
            'celular' => $this->celular,
            'id_direccion' => $this->id_direccion,
            'programa.id_programa' => $this->id_programa,
            'observaciones'=>'1',
            //'confirmacion'=>'1',
            'evento.id_evento' => $id_evento,
            'pago.paquete' => $this->paquete,
        ]);

        $query->andWhere(['confirmacion'=>'1']);
        $query->orFilterWhere([
            'observaciones'=>'2',
            'programa.id_programa' => $this->id_programa,
            'evento.id_evento' => $id_evento,
            //'confirmacion' => 1,
        ]);

        return $dataProvider;
    }

        public function search1($params)
    {
        $query = Cliente::find();

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
            'id_cliente' => $this->id_cliente,
            'cedula' => $this->cedula,
            'telefono' => $this->telefono,
            'celular' => $this->celular,
            'id_direccion' => $this->id_direccion,
            'id_programa' => $this->id_programa,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'correo', $this->correo]);

        return $dataProvider;
    }

    public function search3($params,$fechai,$fechaf)
{
    if(isset($_GET['ClienteSearch'])){

            $this->nombre_programa=$_GET['ClienteSearch']['nombre_programa'];

        }
     if(isset($_GET['ClienteSearch'])){

            $this->nombre_evento=$_GET['ClienteSearch']['nombre_evento'];

        }
    if(isset($_GET['ClienteSearch'])){

               $this->observaciones=$_GET['ClienteSearch']['observaciones'];

           }
    if(isset($_GET['ClienteSearch'])){

                  $this->paquete=$_GET['ClienteSearch']['paquete'];

              }
    $query = Cliente::find()
    ->innerJoin('programa','cliente.id_programa=programa.id_programa')
    ->innerJoin('evento','programa.id_evento=evento.id_evento')
    ->innerJoin('pago','cliente.id_cliente=pago.id_cliente')
    //->where(['evento.fecha_evento'=>$fechai]);
    ->where(['between', 'evento.fecha_evento', $fechai, $fechaf ])
    ->andwhere(['pago.confirmacion'=>1]);
  /*  $query="sELECT * FROM cliente
                  INNER JOIN programa ON cliente.id_programa=programa.id_programa
                  INNER JOIN evento on programa.id_evento=evento.id_evento WHERE evento.fecha_evento";*/
    // add conditions that should always apply here

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);
    /*$dataProvider = new SqlDataProvider([
      'sql' => $query,
    'totalCount' => count($query),
  ]);*/
    $this->load($params);

    if (!$this->validate()) {
        // uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
        return $dataProvider;
    }

    // grid filtering conditions
    $query->andFilterWhere([
        'id_cliente' => $this->id_cliente,
        //'cedula' => $this->cedula,
        'telefono' => $this->telefono,
        'celular' => $this->celular,
        'id_direccion' => $this->id_direccion,
        'id_programa' => $this->id_programa,
    ]);

    $query->andFilterWhere(['like', 'nombre', $this->nombre])
        ->andFilterWhere(['like', 'cedula', $this->cedula])
        ->andFilterWhere(['like', 'correo', $this->correo])
        ->andFilterWhere(['like', 'nombre_evento', $this->nombre_evento])
        ->andFilterWhere(['like', 'observaciones', $this->observaciones])
        ->andFilterWhere(['like', 'pago.paquete', $this->paquete])
        ->andFilterWhere(['like', 'nombre_programa', $this->nombre_programa]);

    return $dataProvider;
}
public function search4($params)
{

if(isset($_GET['Evento'])){

    $id_evento=$_GET['Evento']['id_evento'];
}
else {
$id_evento=null;
}
if(isset($_GET['Cliente'])){
    if($_GET['Cliente']['id_programa']!=0)
    {
      $this->id_programa=$_GET['Cliente']['id_programa'];
    }
    else {
      $this->id_programa=null;
    }
}
$query = Cliente::find();
//$query->innerJoin("pago", "cliente.id_cliente=pago.id_cliente");
//$query->innerJoin("direccion", "cliente.id_direccion=direccion.id_direccion");
$query->innerjoin("programa","programa.id_programa=cliente.id_programa");
$query->innerjoin("evento","evento.id_evento=programa.id_evento");
//$query->with("pagos");

// add conditions that should always apply here

$dataProvider = new ActiveDataProvider([
    'query' => $query,
]);

$this->load($params);

if (!$this->validate()) {
    // uncomment the following line if you do not want to return any records when validation fails

    return $dataProvider;
}

// grid filtering conditions
$query->andFilterWhere([
    'id_cliente' => $this->id_cliente,
    'cedula' => $this->cedula,
    'telefono' => $this->telefono,
    'celular' => $this->celular,
    //'id_direccion' => $this->id_direccion,
    'programa.id_programa' => $this->id_programa,
    //'observaciones'=>'1',
    'evento.id_evento' => $id_evento,
]);

//$query->andWhere(['observaciones'=>'1']);
/*$query->orFilterWhere([
    'observaciones'=>'2',
    'programa.id_programa' => $this->id_programa,
    'evento.id_evento' => $id_evento,
]);*/

return $dataProvider;
}
}
