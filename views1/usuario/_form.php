<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuario-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="col-md-4">
    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-md-4">
    <?= $form->field($model, 'clave')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'id_rol')->dropDownlist($roles,['prompt' => 'Seleccione Uno' ]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'cedula')->textInput() ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
    <?= $form->field($model, 'foto')->fileInput(['maxlength' => true]) ?>
  </div>
  <div class="col-md-4">
  </div>
  <div class="col-md-12">
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
    </div>
  </div>
    <?php ActiveForm::end(); ?>

</div>
