<?php

use yii\helpers\ArrayHelper;
use app\models\Evento;
use app\models\Programa;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cliente-form">

    <!--?php $form = ActiveForm::begin(); ?-->
    <?php $form = ActiveForm::begin([
      "method" => "get",
      "action" => Url::toRoute("cliente/correo"),
      "enableClientValidation" => true,
    ]); ?>


    <!-- Main content -->
    
      <div class="row">
     
        <div class="col-md-3">
        </div> 

        <div class="col-md-6">
          <!-- general form elements -->

            
              <h3 class="box-title" align="center">Buscar Promocion</h3>                 
           
                <div class="form-group">
               
          
    <div class="cliente-form">
    
        <?= $form->field(new app\models\Evento(), 'id_evento')->dropDownList(
            ArrayHelper::map(\app\models\Evento::find()->all(), 'id_evento', 'nombre_evento'),
                        [ 
                            'prompt'=>'Seleccion',
                            'onchange'=>'
                            $.post( "../../index.php/programa/list?id='.'"+$(this).val(), function(data) {
                                $( "#'.Html::getInputId($model, 'id_programa').'" ).html( data );
                            });'
                            
                    ]); 
            echo $form->field($model, 'id_programa')->dropDownList(['prompt'=>'Seleccion']) 
?>

    </div>
                 <div class="form-group" align="center">
                 <?= Html::submitButton('Buscar', ['class' => 'btn btn-success']) ?>
                 </div>

             <?php ActiveForm::end(); ?>
              </div>
        <div class="col-md-3">
        </div>    
       




<!--h3><?= $search ?></h3-->
<br>
<br>

 <?php $forme = ActiveForm::begin([
      "method" => "post",
      "action" => Url::toRoute("cliente/correo"),
      "enableClientValidation" => true,
    ]); ?>

<h3 align="center">Lista de Clientes</h3>
<!--table class="table table-bordered"   textInput-->
  
  
  <div class="row">
    <div class="col-md-1"> <th>Cliente</th></div>
    <div class="col-md-1"> <th>Nombre</th> </div>
    <div class="col-md-1"> <th>Apellido</th> </div>
    <div class="col-md-1"> <th>Correo</th> </div>
    <div class="col-md-1"> <th>Telefono</th> </div>
    <div class="col-md-1"> <th>Celular</th> </div>
    <div class="col-md-1"> <th>Programa</th> </div>
    <div class="col-md-1"> <th>Enlace</th> </div>
  </div>
    <?php 
    $i=0;
    foreach($model1 as $row): ?>
     <div class="row">
      <?php $idcliente=$row->id_cliente; ?>
    <div class="col-md-1"><?= $row->id_cliente ?>  <?=  $forme->field($enlace, "id[$i]")->hiddenInput(['value' => $idcliente])->label(false);?></div>
    <div class="col-md-1"><?= $row->nombre ?> <?=  $forme->field($enlace, "nombre[$i]")->hiddenInput(['value' => $row->nombre])->label(false);?></div>
    <div class="col-md-1"><?= $row->apellido ?> <?=  $forme->field($enlace, "apellido[$i]")->hiddenInput(['value' => $row->apellido])->label(false);?></div>
    <div class="col-md-1"><?= $row->correo ?> <?=  $forme->field($enlace, "correo[$i]")->hiddenInput(['value' => $row->correo])->label(false);?></div>
    <div class="col-md-1"><?= $row->telefono ?></div>
    <div class="col-md-1"><?= $row->celular ?></div>
    <div class="col-md-1"><?= $row->id_programa ?></div>
    <div class="col-md-1"><?= $forme->field($enlace, "link[$i]")->label(false); ?></div>
  </div>
 


    <?php 
    $i++;
  endforeach ?>
  
<!--/table-->

          <div class="form-group" align="center">
                 <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                 </div>

 <?php ActiveForm::end(); ?>

</div>
