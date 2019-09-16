<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$context = $this->context;

//$context->layout = 'layouts/payment'; // use specific layout for this template
// specify particular PDF conversion for this template:
$context->pdfOptions = [
    'pageSize' => 'A4-L',
    // ...
];
//$this->title = 'Clientes';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cliente-index">

    <h4><?= Html::encode($this->title) ?></h4>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        //'options' => ['style' => 'font-size:12px;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [   'label' => 'idcliente',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'idcliente',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [   
                'label' =>'cedula',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'cedula',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'nombre',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'nombre',
                'contentOptions' => ['style' => 'font-size:80px;']
            ],
            [
                'label' =>'nombre_fantasia',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'nombre_fantasia',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'sociedad',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'sociedad',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'email1',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'email1',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'email2',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'email2',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'telefono',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'telefono',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'celular',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'celular',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'lugar_residencia',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'lugar_residencia',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'clave_atv',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'clave_atv',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'comentario',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'comentario',
                'contentOptions' => ['style' => 'font-size:80px;']],
            [
                'label' =>'idtipocliente',
                'headerOptions' => ['style' => 'font-size:80px;'],
                'attribute' =>'idtipocliente',
                'contentOptions' => ['style' => 'font-size:80px;']],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
