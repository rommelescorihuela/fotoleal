<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PagoFotoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pago-foto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_pago_foto') ?>

    <?= $form->field($model, 'numero_referencia') ?>

    <?= $form->field($model, 'monto') ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'observaciones') ?>

    <?php // echo $form->field($model, 'id_usuario') ?>

    <?php // echo $form->field($model, 'tipo_pago') ?>

    <?php // echo $form->field($model, 'fecha_pago') ?>

    <?php // echo $form->field($model, 'paquete') ?>

    <?php // echo $form->field($model, 'ref_payco') ?>

    <?php // echo $form->field($model, 'confirmacion') ?>

    <?php // echo $form->field($model, 'factura') ?>

    <?php // echo $form->field($model, 'ref_payco_corto') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
