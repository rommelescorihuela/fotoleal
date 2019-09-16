<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Button;
use kartik\export\ExportMenu;
use kartik\date\DatePicker;
use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */

$this->title = 'Fotografia Leal';
?>
<div class="reporte-index">


    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'reporte-form',
    ]); ?>

<div class="col-md-4"> 
<?php
    echo DatePicker::widget([
    'model' => $model, 
    'attribute' => 'fecha1',
    'options' => ['placeholder' => 'Fecha de inicio de busqueda'],
        'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autoclose' => true,
    ]
    ]);
?>
</div>
<div class="col-md-4">
<?php
    echo DatePicker::widget([
    'model' => $model, 
    'attribute' => 'fecha2',
    'options' => ['placeholder' => 'Fecha de fin de busqueda'],
    'pluginOptions' => ['autoclose'=>true]
    ]);
?>
</div>   

<div class="col-md-4">
<?= Html::submitButton('Buscar', ['class' => 'btn btn-primary', 'name' => 'buscar-button']) ?>
</div>

    <?php ActiveForm::end(); ?>