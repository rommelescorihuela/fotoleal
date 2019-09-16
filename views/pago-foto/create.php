<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PagoFoto */

$this->title = 'Create Pago Foto';
$this->params['breadcrumbs'][] = ['label' => 'Pago Fotos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pago-foto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
