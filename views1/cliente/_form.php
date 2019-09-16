<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cliente-form">


    <?php $form = ActiveForm::begin(); ?>
    <?php $id=0;?>
    <div class="col-md-4">
        <?= $form->field(new app\models\Evento(), 'id_evento')->dropDownList(
            ArrayHelper::map(\app\models\Evento::find()->all(), 'id_evento', 'nombre_evento'),
                        [
                            'prompt'=>'Seleccion',
                            'onchange'=>'
                            $.post( "../../index.php/programa/list?id='.'"+$(this).val(), function(data) {
                                $( "#'.Html::getInputId($model, 'id_programa').'" ).html( data );
                            });'

                    ]);
          ?></div> <div class='col-md-4'><?php
            echo  $form->field($model, 'id_programa')->dropDownList([null=>'Seleccione'],
          ['options' =>
                    [
                      $id => ['selected' => true]
                    ]
          ])

?>
</div>
    <div class="col-md-4">
    <?= $form->field($model, 'cedula')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'celular')->textInput() ?>
  </div>
  <div class="col-md-4">
    <?php //echo $form->field($model, 'id_programa')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'id_direccion')->hiddenInput()->label(false) ?>
  </div>
  <div class="col-md-4">
    <b>Direccion</b>
    <div class="col-md-12">
        <div class="col-md-6">
            <?= $form->field($modeldireccion, 'municipio')->dropDownlist([0=>'Seleccione','Bogota'=>'Bogota']); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($modeldireccion, 'barrio')->textInput() ?>
        </div>
    </div>
    <div class="col-md-12">
 <div class="col-md-3"><?php $var = [ 'Cl' => 'Cl', 'Cra' => 'Cra',  'Tvr' => 'Tvr','Dg' => 'Dg'];
      echo $form->field($modeldireccion, 'tipo_carrera')->dropDownList($var, ['prompt' => 'Seleccione' ])->label(false);?>
    </div>
        <div class="col-md-3">
            <?= $form->field($modeldireccion, 'carrera1')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modeldireccion, 'carrera2')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($modeldireccion, 'carrera3')->textInput() ?>
        </div>
    </div>
    <div class='col-md-12'>
        <div class="col-md-4" ><?php $var = [ 'Casa' => 'Casa', 'Torre' => 'Torre',  'Apto' => 'Apto'];
      echo $form->field($modeldireccion, 'tipo_casa')->dropDownList($var, ['prompt' => 'Seleccione' ])->label(false);?>
</div>
        <div class="col-md-4">
            <?= $form->field($modeldireccion, 'casa1')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($modeldireccion, 'casa2')->textInput() ?>
        </div>
    </div>
  </div>
    <div class="form-group">
        <?= Html::submitButton('GUARDAR', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
