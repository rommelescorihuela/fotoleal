<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PagoFoto */

$this->title = 'Update Pago Foto: ' . $model->id_pago_foto;
$this->params['breadcrumbs'][] = ['label' => 'Pago Fotos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_pago_foto, 'url' => ['view', 'id' => $model->id_pago_foto]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pago-foto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
