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
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Cliente;
use app\models\ClienteSearch;

/* @var $this yii\web\View */

$this->title = 'Fotografia Leal';
?>
<div class="reporte-index">


    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'id' => 'reporte-form',
        'action' => 'guardarpago',
    ]); ?>


     <div class="col-md-12" >
   <?php
   $data = Cliente::find()
        ->select(['cedula as value'])
        ->innerjoin("programa","programa.id_programa=cliente.id_programa")
        ->innerjoin("evento","evento.id_evento=programa.id_evento")
        ->andWhere(['evento.cerrado'=>'0'])
        ->asArray()
        ->all();
        echo '<b>Cedula</b>' .'<br>';
    echo AutoComplete::widget([
    'options'=>['id'=>'cliente-cedula','class'=>'form-control','name'=>'Cliente[cedula]'],
    'clientOptions' => [
    'source' => $data,
    'minLength'=>'7',
    'autoFill'=>true,
    ],
                 ]);
            ?>
    </div> 
<div class="col-md-12" style="height: 20px;"></div>
<div class="col-md-12" id="datos" style="display: none;">    
<div class="col-md-2">cedula:</div><div class="col-md-10" id="info"></div>
<div class="col-md-2">nombre:</div><div class="col-md-10" id="nombre"></div>
<div class="col-md-2">evento:</div><div class="col-md-10" id="evento"></div>
<div class="col-md-2">programa:</div><div class="col-md-10" id="programa"></div>
<div class="col-md-2">cantidad de fotos:</div><div class="col-md-10" id="cantidad_foto"></div>
<div class="col-md-2">monto total a pagar:</div><div class="col-md-2" id="total1"><div class="col-md-8"></div>
    <?= $form->field($model, 'monto')->textInput(["readonly"=>true])->label(false) ?>
    <?= $form->field($model, 'id_cliente')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'id_foto_adicional')->hiddenInput()->label(false) ?>
        
    </div>
<div class="col-md-12" style="height: 20px;"></div>    
<div class="col-md-12">
<?= Html::submitButton('Pagar', ['class' => 'btn btn-primary', 'name' => 'buscar-button']) ?>
</div>
</div> 

    <?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
  $("#cliente-cedula").blur(function(){
      $.ajax({
       url: '<?php echo Yii::$app->request->baseUrl. '/index.php/site/fotocliente' ?>',
       type: 'post',
       data: {
                 cedula: $("#cliente-cedula").val() ,
             },
       success: function (data) {
        $( "#datos" ).show();
        $( "#info" ).html(data['cedula']);
        $( "#nombre" ).html(data['nombre']);
        $( "#evento" ).html(data['nombre_evento']);
        $( "#programa" ).html(data['nombre_programa']);
        $( "#cantidad_foto" ).html(data['cantidad_foto']);
        $( "#monto" ).html(data['monto']);
        $( "#pagofoto-monto" ).val(data['total']);
        $( "#pagofoto-id_cliente" ).val(data['id_cliente']);
        $( "#pagofoto-id_foto_adicional" ).val(data['id_foto_adicional']);
        
       }
  });
  });
});
</script>