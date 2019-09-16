<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FotoAdicional */

$this->title = 'Create Foto Adicional';
$this->params['breadcrumbs'][] = ['label' => 'Foto Adicionals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="foto-adicional-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'cedula'=>$cedula,
    ]) ?>

</div>
