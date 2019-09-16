<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FotoAdicionalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="foto-adicional-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id_foto') ?>

    <?= $form->field($model, 'cantidad_foto') ?>

    <?= $form->field($model, 'monto') ?>

    <?= $form->field($model, 'total') ?>

    <?= $form->field($model, 'id_usuario') ?>

    <?php // echo $form->field($model, 'id_cliente') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
