<?php

use yii\helpers\ArrayHelper;
use app\models\Evento;
use app\models\Programa;
use app\models\Direccion;
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
      "method" => "get",
      "action" => Url::toRoute("cliente/envio"),
      "enableClientValidation" => true,
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


/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Clientes';
//$this->params['breadcrumbs'][] = $this->title;
?>

		<h3 align="center" style="color:green;">Lista de Clientes Con Pagos completos</h3>

  <div class="col-md-12">

<div class="cliente-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    $gridColumns=[
        ['class' => 'yii\grid\SerialColumn'],
        'nombre',
        //'apellido',
        'telefono',
        'celular',
        'id_programa',
        //'municipio',
        [

            'label'=>'Ciudad / Municipio',
            'format'=>'text',//raw, html
            'content'=>function($data){
              $direccion=Direccion::find()->where(["id_direccion"=>$data->id_direccion])->one();
                return $direccion->municipio;
            }
        ],
        [

            'label'=>'Direccion',
            'format'=>'text',//raw, html
            'content'=>function($data){
              $direccion=Direccion::find()->where(["id_direccion"=>$data->id_direccion])->one();
                return $direccion->tipo_carrera.' '.$direccion->carrera1.' #'.$direccion->carrera2.' - '.$direccion->carrera3.' '.$direccion->tipo_casa.' '.$direccion->casa1.' '.$direccion->casa2;
            }
        ],
        /*[

            'label'=>'Cl,Cr,Trv,Dg',
            'format'=>'text',//raw, html
            'content'=>function($data){
              $direccion=Direccion::find()->where(["id_direccion"=>$data->id_direccion])->one();
                return $direccion->tipo_carrera;
            }
        ],
        [

            'label'=>'',
            'format'=>'text',//raw, html
            'content'=>function($data){
              $direccion=Direccion::find()->where(["id_direccion"=>$data->id_direccion])->one();
                return $direccion->carrera1;
            }
        ],
        [

            'label'=>'#',
            'format'=>'text',//raw, html
            'content'=>function($data){
              $direccion=Direccion::find()->where(["id_direccion"=>$data->id_direccion])->one();
                return $direccion->carrera2;
            }
        ],
        [

            'label'=>'',
            'format'=>'text',//raw, html
            'content'=>function($data){
              $direccion=Direccion::find()->where(["id_direccion"=>$data->id_direccion])->one();
                return $direccion->carrera3;
            }
        ],
        [

            'label'=>'Casa,Tr,Int',
            'format'=>'text',//raw, html
            'content'=>function($data){
              $direccion=Direccion::find()->where(["id_direccion"=>$data->id_direccion])->one();
                return $direccion->tipo_casa;
            }
        ],
        [

            'label'=>'#',
            'format'=>'text',//raw, html
            'content'=>function($data){
              $direccion=Direccion::find()->where(["id_direccion"=>$data->id_direccion])->one();
                return $direccion->casa1;
            }
        ],
        [

            'label'=>'casa',
            'format'=>'text',//raw, html
            'content'=>function($data){
              $direccion=Direccion::find()->where(["id_direccion"=>$data->id_direccion])->one();
                return $direccion->casa2;
            }
        ],*/
        [

            'label'=>'Paquete',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activeDropDownList($searchModel, 'paquete', [''=>'Seleccione',1=>'Paquete premium digital',0=>'Paquete premium'],['class'=>'form-control',]) ,
            'content'=>function($data){
                $pago=Pago::find()->where(["id_cliente"=>$data->id_cliente])->one();
                if($pago->paquete==0)
                {
                  return '<b style="color:green">Paquete premium</b>';
                }
                else {
                  return '<b style="color:blue">Paquete premium digital</b>';
                }

            }
        ],
        //['class' => 'yii\grid\ActionColumn'],
    ];


// You can choose to render your own GridView separately
echo \kartik\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'defaultPagination' => 'all',
    'columns' => $gridColumns,
    
]);

echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'stream' => false,
    //'folder'=>'@app/runtime/export/',
    'folder'=>'@app/runtime/export/',
    'linkPath' => '../../runtime/export/',
    'exportConfig' => [
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_PDF => false,
        ExportMenu::FORMAT_HTML => false,
        ExportMenu::FORMAT_CSV => false,
        ExportMenu::FORMAT_EXCEL_X => false
    ],
    'messages'=>['confirmDownload'=>false,'allowPopups'=>false,'downloadProgress'=>false],
    'filename'=>'Reporte_de_envio',
    'dropdownOptions'=>[
        'label'=>'Descargar archivo',
        'icon'=>'<i class=""></i>',
    ],
    //'folder'=>'@app/runtime/export/.'
    //'target'=>[ExportMenu::TARGET_SELF => false]
]);
?>
    <?php /*GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_cliente',
            //'cedula',
            'nombre',
            'apellido',
            //'correo',
            'telefono',
            'celular',
            'id_direccion',
            //'id_programa',
            //'programa.nombre_programa',
        ],
    ]); */?>


</div>
  </div>

<!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx modulo de notificacion xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->
 <div class="content">


    <!--?php $form = ActiveForm::begin(); ?-->
    <?php $form = ActiveForm::begin([
      "method" => "post",
      "action" => Url::toRoute("cliente/notificacion"),
      "enableClientValidation" => true,

    ]); ?>


<!-- Main content -->



    <div class="col-md-12">
      <!-- general form elements -->

          <h3 class="box-title" align="center">Notificación</h3>


  <div class="col-md-3"></div>

<div class="col-md-6">

    <?= $form->field($model, 'correo')->input('correo') ?>
    <?= $form->field($model, 'evento')->hiddenInput(['value'=>$evento])->label(false) ?>
    <?= $form->field($model, 'programa')->hiddenInput(['value'=>$id_programa])->label(false) ?>
                 <div class="form-group" align="center">
                    <?= Html::submitButton('Enviar notificacion', ['class'=>'btn btn-default']) ?>
                 </div>
</div>
<div class="col-md-3"></div>




             <?php ActiveForm::end(); ?>

            </div>


</div>
