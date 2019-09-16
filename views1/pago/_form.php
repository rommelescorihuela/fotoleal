<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Usuario;
use app\models\Cliente;
/* @var $this yii\web\View */
/* @var $model app\models\Pago */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pago-form">

    <?php $form = ActiveForm::begin(); ?>
  <div class="col-md-3">
    <?= $form->field($model, 'numero_referencia')->textInput() ?>
  </div>
<div class="col-md-3">
  <?= $form->field($model, 'monto')->textInput() ?>
</div>

<div class="col-md-3">
  <?php
  $var = \yii\helpers\ArrayHelper::map(Cliente::find()->all(), 'id_cliente', 'nombre');;
  echo $form->field($model, 'id_cliente')->dropDownList($var, ['prompt' => 'Seleccione Uno' ])->label('Cliente');
  ?>
</div>
<div class="col-md-3">
  <?php
  $var = [ 1 => 'Pago total',  0 => 'Primer abono', 2 => 'Segundo abono'];
  echo $form->field($model, 'observaciones')->dropDownList($var, ['prompt' => 'Seleccione Uno' ]);
  ?>
</div>
<div class="col-md-3">
  <?php
  $var = \yii\helpers\ArrayHelper::map(Usuario::find()->all(), 'id', 'nombre');;
  echo $form->field($model, 'id_usuario')->dropDownList($var, ['prompt' => 'Seleccione Uno' ])->label('Usuario');
  ?>
</div>
<div class="col-md-3">
      <?php
      $var = [ 'electronico' => 'electronico',  'efectivo' => 'efectivo'];
      echo $form->field($model, 'tipo_pago')->dropDownList($var, ['prompt' => 'Seleccione Uno' ]);
      ?>
</div>

<div class="col-md-3">
    <?= $form->field($model, 'fecha_pago')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-md-3">
    <?php
    $var = [ '1' => 'Paquete premium',  '0' => 'Paquete digital'];
    echo $form->field($model, 'paquete')->dropDownList($var, ['prompt' => 'Seleccione Uno' ]);
    ?>
  </div>
  <div class="col-md-3">
    <?= $form->field($model, 'ref_payco')->textInput(['maxlength' => true])->label('Ref.payco temporal') ?>
  </div>
  <div class="col-md-3">
    <?php
    $var = [ 1 => 'Confirmado',  3 => 'Pendiente'];
    echo $form->field($model, 'confirmacion')->dropDownList($var, ['prompt' => 'Seleccione Uno' ]);
    ?>
  </div>
  <div class="col-md-3">
    <?= $form->field($model, 'factura')->textInput(['maxlength' => true])->label('Ref.cliente') ?>
  </div>
  <div class="col-md-3">
    <?= $form->field($model, 'ref_payco_corto')->textInput(['maxlength' => true])->label('Ref.payco') ?>
  </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

