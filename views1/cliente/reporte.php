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
use app\models\TipoCliente;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reportes';

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

    

    <?php Pjax::begin(['id' => 'admin-crud-id']); ?>
    <?= GridView::widget([
        'id' => 'admin-crud-id',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idcliente',
            'cedula',
            'nombre',
            'nombre_fantasia',
            'sociedad',
            //'email1:email',
            //'email2:email',
            //'telefono',
            //'celular',
            //'lugar_residencia',
            //'clave_atv',
            //'comentario',
            'fecha_ingreso',
            [
            'attribute' => 'Tipo de cliente',
            'value' => 'tipocliente.tipo_cliente',
            //'filter'=>ArrayHelper::map(Roles::find()->asArray()->all(), 'idrol', 'rol'),
            'filter' => Html::activeDropDownList($searchModel, 'idtipocliente', ArrayHelper::map(Tipocliente::find()->asArray()->all(), 'idtipocliente', 'tipo_cliente'),['class'=>'form-control','prompt' => 'Seleccione']),
 ],
           

            //['class' => 'yii\grid\ActionColumn'],
        ],

    ]); 
    Pjax::end();?>
    <?php echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
            'idcliente',
            'cedula',
            'nombre',
            'nombre_fantasia',
            'sociedad',
            'email1:email',
            'email2:email',
            'telefono',
            'celular',
            'lugar_residencia',
            'clave_atv',
            'comentario',
            'tipocliente.tipo_cliente',
            
        ],
    'options' => ['id'=>'expMenu2'], // optional to set but must be unique
    'exportConfig' => [
            ExportMenu::FORMAT_TEXT => false,
            ExportMenu::FORMAT_HTML => false,
            ExportMenu::FORMAT_CSV => false,           
    ],
    'target' => ExportMenu::TARGET_BLANK
]);?>
</div>
