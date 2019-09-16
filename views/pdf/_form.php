<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cliente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cedula')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre_fantasia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sociedad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'celular')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lugar_residencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clave_atv')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comentario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idtipocliente')->dropdownList($droptc,['prompt'=>'Seleccione']) ?>
    
    <?= $form->field($modelotc, 'respuesta')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($modelotc, 'conoce')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
