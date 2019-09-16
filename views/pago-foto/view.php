<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PagoFoto */

$this->title = $model->id_pago_foto;
$this->params['breadcrumbs'][] = ['label' => 'Pago Fotos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pago-foto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_pago_foto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_pago_foto], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_pago_foto',
            'numero_referencia',
            'monto',
            'id_cliente',
            'observaciones',
            'id_usuario',
            'tipo_pago',
            'fecha_pago',
            'paquete',
            'ref_payco',
            'confirmacion',
            'factura',
            'ref_payco_corto',
        ],
    ]) ?>

</div>
