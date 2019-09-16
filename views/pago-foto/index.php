<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PagoFotoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pago Fotos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pago-foto-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pago Foto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_pago_foto',
            'numero_referencia',
            'monto',
            'id_cliente',
            'observaciones',
            //'id_usuario',
            //'tipo_pago',
            //'fecha_pago',
            //'paquete',
            //'ref_payco',
            //'confirmacion',
            //'factura',
            //'ref_payco_corto',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
