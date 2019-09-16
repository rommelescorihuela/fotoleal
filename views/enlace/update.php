<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Enlace */

$this->title = 'Update Enlace: ' . $model->id_enlace;
$this->params['breadcrumbs'][] = ['label' => 'Enlaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_enlace, 'url' => ['view', 'id' => $model->id_enlace]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="enlace-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
