<?php

use yii\helpers\ArrayHelper;
use app\models\Evento;
use app\models\Programa;
use app\models\Direccion;
use app\models\Cliente;
use app\models\Pago;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>

<?= \dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]) ?>


<div class="cliente-form">

    <!--?php $form = ActiveForm::begin(); ?-->
    <?php $form = ActiveForm::begin([
      "method" => "post",
      "action" => Url::toRoute("cliente/listadopago"),
      "enableClientValidation" => true,
      'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>


    <!-- Main content -->
      <div class="content">

        <div class="col-md-3">
        </div>

        <div class="col-md-6">
          <!-- general form elements -->


              <h3 class="box-title" align="center">Busqueda por Programa para Envio</h3>

                <div class="form-group">


    <div class="cliente-form">
    <?php $id=0;?>

        <?= $form->field(new app\models\Evento(), 'id_evento')->dropDownList(
            ArrayHelper::map(\app\models\Evento::find()->all(), 'id_evento', 'nombre_evento'),
                        [
                            'prompt'=>'Seleccion',
                            'onchange'=>'
                            $.post( "../../index.php/programa/listado/?id='.'"+$(this).val(), function(data) {
                                $( "#'.Html::getInputId($model, 'id_programa').'" ).html( data );
                            });'

                    ]);
            echo $form->field($model, 'id_programa')->dropDownList(['0'=>'Seleccione'],

            ['options' =>
                    [
                      $id => ['selected' => true]
                    ]
          ]

        )
?>

    </div>
    <div class="form-group">
<?= $form->field($model, 'carga')->fileInput() ?>
<b>Nota: el archivo debe ser de extension Xls compatible con Excel 97 - 2003</b>
    </div>
                 <div class="form-group" align="center">
                 <?= Html::submitButton('Buscar', ['class'=>'btn btn-default']) ?>
                 </div>

             <?php ActiveForm::end(); ?>
              </div>
</div>
        <div class="col-md-3">
        </div>

  </div>
<?php
$gridColumns1=[
    ['class' => 'yii\grid\SerialColumn'],
    'facultad',
    'programa',
    'nombre',
    'bloque',
    'fila',
    'silla',
    'cedula',
    //'id_programa',
    [

        'label'=>'status',
        'format'=>'text',//raw, html
        'content'=>function($data,$programa1){
          $p='pago';

          $pago = Pago::find()
          ->innerJoin("cliente", "cliente.id_cliente=pago.id_cliente")
          ->innerJoin("direccion", "cliente.id_direccion=direccion.id_direccion")
          ->innerjoin("programa","programa.id_programa=cliente.id_programa")
          ->innerjoin("evento","evento.id_evento=programa.id_evento")
          ->where(["cliente.cedula"=>$data['cedula'],"programa.id_programa"=>$data['id_programa']])
          ->all();
          foreach ($pago as $k ) {
            $p.=$k['observaciones'];
          }
            if($p=='pago')
            {
              return 'Sin pago';
            }
            elseif ($p=='pago0') {
              return 'Abono';
            }
            elseif ($p=='pago02') {
              return 'Pago total';
            }
            elseif ($p=='pago1') {
              return 'Pago total';
            }
            //return $pa;
            //return $data['cedula'];
        }
    ],

];
echo Html::a('descargar archivo', ['../archivos/reportepago.xls'], ['class'=>'btn btn-primary']);

echo \kartik\grid\GridView::widget([
    'dataProvider' => $provider,
    //'filterModel' => $searchModel,
    'columns' => $gridColumns1
]);

?>

</div>
  </div>
