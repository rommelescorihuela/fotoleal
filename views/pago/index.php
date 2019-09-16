<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Programa;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PagoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pagos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pago-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pago', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_pago',
            //'numero_referencia',
            //'id_cliente',
            [

            'label'=>'Cliente',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activetextinput($searchModel,'nombre') ,
            'content'=>function($data){
                return $data->cliente->nombre;
                //var_dump($data->cliente->nombre);
             
            }
        ],
        [

            'label'=>'Cedula',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activetextinput($searchModel,'cedula') ,
            'content'=>function($data){
                return $data->cliente->cedula;
                //var_dump($data->cliente->cedula);
             
            }
        ],
        [

            'label'=>'Programa',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activetextinput($searchModel,'programa') ,
            'content'=>function($data){
                //return $data->cliente->id_programa;
                return Programa::find()->where(['id_programa'=>$data->cliente->id_programa])->one()->nombre_programa;
                //var_dump($data->cliente->nombre);
             
            }
        ],
            //'cliente.nombre',
            'tipo_pago',
             'monto',
            //'observaciones',
            //'confirmacion',
            //'ref_payco_corto',
             [

            'label'=>'Modo de pago',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activeDropDownList($searchModel, 'observaciones', [''=>'Seleccione',1=>'Pago total',0=>'Primer abono', 2=>'Segundo abono'],['class'=>'form-control']) ,
            'content'=>function($data){
              if($data->observaciones==1)
              {
                  return '<b style="color:green;">Pago total</b>';
              }
              elseif($data->observaciones==0)
              {
                  return '<b style="color:orange;">Primer abono</b>';
              }
              elseif($data->observaciones==2)
              {
                  return '<b style="color:black;">Segundo abono</b>';
              }
            }
        ],
            [

            'label'=>'Ref.payco',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activetextinput($searchModel,'ref_payco_corto') ,
            'content'=>function($data){
                return $data->ref_payco_corto;
                //var_dump($data->cliente->cedula);
             
            }
        ],
            [

            'label'=>'Ref.cliente',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activetextinput($searchModel,'factura') ,
            'content'=>function($data){
                return $data->factura;
                //var_dump($data->cliente->cedula);
             
            }
        ],
            
            [

            'label'=>'Estatus de pago',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activeDropDownList($searchModel, 'confirmacion', [''=>'Seleccione',1=>'Pago confirmado',3=>'pago pendiente por confirmar'],['class'=>'form-control']) ,
            'content'=>function($data){
              if($data->confirmacion==1)
              {
                  return '<b style="color:green;">Pago confirmado</b>';
              }
              elseif($data->confirmacion==3)
              {
                  return '<b style="color:orange;">pago pendiente por confirmar</b>';
              }
            }
        ],
        
            //'id_usuario',
            'fecha_pago',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
