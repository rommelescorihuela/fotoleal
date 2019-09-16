<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PagoFoto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pago-foto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'numero_referencia')->textInput() ?>

    <?= $form->field($model, 'monto')->textInput() ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <?= $form->field($model, 'observaciones')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_usuario')->textInput() ?>

    <?= $form->field($model, 'tipo_pago')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_pago')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paquete')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ref_payco')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'confirmacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'factura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ref_payco_corto')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
