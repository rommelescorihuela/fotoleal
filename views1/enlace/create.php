<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Enlace */

$this->title = 'Create Enlace';
$this->params['breadcrumbs'][] = ['label' => 'Enlaces', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="enlace-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
