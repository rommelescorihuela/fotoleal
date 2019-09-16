<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dominus77\sweetalert2\Alert;


/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= \dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]) ?>

<div class="cliente-index">



    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="col-md-10">
        <?= Html::a('Carga masiva de Clientes', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="col-md-2">
    <?= Html::a('Crear Cliente', ['create1'], ['class' => 'btn btn-success']) ?>
  </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel1,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_cliente',
            'cedula',
            'nombre',
            //'apellido',
            'correo',
            //'telefono',
            //'celular',
            //'id_direccion',
            //'id_programa',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
