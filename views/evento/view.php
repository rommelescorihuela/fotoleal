<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Evento */

$this->title = $model->id_evento;
$this->params['breadcrumbs'][] = ['label' => 'Eventos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="evento-view">

    

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_evento], ['class' => 'btn btn-primary']) ?>
        <?php
        if($model->cerrado==1)
        {
          echo "<h1>evento cerrado</h2>";
        }
        ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id_evento',
            'nombre_evento',
            //'id_direccion',
            'fecha_evento',
            'monto_evento',
            'paquete',
            'monto_evento',
            'abono',
            'saldo',
            'paquete2',
            'monto_evento2',
            'abono2',
            'saldo2',
           
        ],
    ]) ?>

</div>
