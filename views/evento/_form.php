<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Evento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="evento-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <div class="col-md-6">
        <?= $form->field($model, 'nombre_evento')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">

    <?= $form->field($model, 'fecha_evento')->widget(DatePicker::classname(), [
                           'language' => 'es',
                           'options' => ['placeholder' => 'Fecha del Evento'],
                            'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true,
    ]

                      ])->label(); ?>
</div>
    </div>
<div class="col-md-12">
<div class="col-md-6">
    <?= $form->field($model, 'paquete')->textInput() ?>
</div>
<div class="col-md-2">
    <?= $form->field($model, 'monto_evento')->textInput() ?>
</div>
<div class="col-md-2">
    <?= $form->field($model, 'abono')->textInput() ?>
</div>
<div class="col-md-2">
    <?= $form->field($model, 'saldo')->textInput(['readonly'=>true]) ?>
</div>

 </div>
 <div class="col-md-12">
<div class="col-md-6">
    <?= $form->field($model, 'paquete2')->textInput() ?>
</div>
<div class="col-md-2">
    <?= $form->field($model, 'monto_evento2')->textInput() ?>
</div>
<div class="col-md-2">
    <?= $form->field($model, 'abono2')->textInput() ?>
</div>
<div class="col-md-2">
    <?= $form->field($model, 'saldo2')->textInput(['readonly'=>true]) ?>
</div>

 </div>
 <div class="col-md-12">

    <?= $form->field($model, 'cerrado')->checkbox() ?>
</div>
 
    



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
$(document).ready(function(){
  $("#evento-abono").blur(function(){
    monto=$("#evento-monto_evento").val();
    abono=$("#evento-abono").val();
    saldo=monto-abono;
    $("#evento-saldo").val(saldo);
  });
  $("#evento-abono2").blur(function(){
    monto2=$("#evento-monto_evento2").val();
    abono2=$("#evento-abono2").val();
    saldo2=monto2-abono2;
    $("#evento-saldo2").val(saldo2);
  })
  })
</script>