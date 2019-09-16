<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FotoAdicional */

$this->title = 'Update Foto Adicional: ' . $model->id_foto;
$this->params['breadcrumbs'][] = ['label' => 'Foto Adicionals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_foto, 'url' => ['view', 'id' => $model->id_foto]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="foto-adicional-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cedula'=>$cedula,
    ]) ?>

</div>
