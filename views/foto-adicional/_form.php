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
/* @var $model app\models\FotoAdicional */
/* @var $form yii\widgets\ActiveForm */
//var_dump(Yii::$app->user->identity->id_usuario);
?>

<div class="foto-adicional-form">

    <?php $form = ActiveForm::begin(); ?>
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
    'value'=>$cedula,
                 ]);
            ?>
<div class="col-md-12" style="display: none;" id="datos">
<div class="col-md-2">nombre:</div><div class="col-md-10" id="nombre"></div>
<div class="col-md-2">evento:</div><div class="col-md-10" id="evento"></div>
<div class="col-md-2">programa:</div><div class="col-md-10" id="programa"></div>
</div>
    <?= $form->field($model, 'cantidad_foto')->textInput() ?>

    <?= $form->field($model, 'monto')->textInput() ?>

    <?= $form->field($model, 'total')->textInput(["readonly"=>true]) ?>

    <?= $form->field($model, 'id_usuario')->hiddenInput(["value"=> 1,"readonly"=>true])->label(false) ?>

    <?= $form->field($model, 'id_cliente')->hiddenInput(["readonly"=>true])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $(document).ready(function(){
//////////////////////////////////////////////////////////////////////////////////////////77        
  $("#cliente-cedula").blur(function(){
      $.ajax({
       url: '<?php echo Yii::$app->request->baseUrl. '/index.php/site/fotocliente1' ?>',
       type: 'post',
       data: {
                 cedula: $("#cliente-cedula").val() ,
             },
       success: function (data) {
        $( "#datos" ).show();
        //$( "#info" ).html(data['cedula']);
        $( "#nombre" ).html(data['nombre']);
        $( "#evento" ).html(data['nombre_evento']);
        $( "#programa" ).html(data['nombre_programa']);
        //$( "#cantidad_foto" ).html(data['cantidad_foto']);
        //$( "#monto" ).html(data['monto']);
        //$( "#pagofoto-monto" ).val(data['total']);
        $( "#fotoadicional-id_cliente" ).val(data['id_cliente']);
        //$( "#pagofoto-id_foto_adicional" ).val(data['id_foto_adicional']);
        
       }, 
        error: function (jqXHR, textStatus, errorThrown) { 
        $( "#datos" ).show();
        $( "#nombre" ).html("No se encontro el cliente intente de nuevo");
        $( "#evento" ).html("No se encontro el cliente intente de nuevo");
        $( "#programa" ).html("No se encontro el cliente intente de nuevo");
    }
     
  });
  });
////////////////////////////////////////////////////////////////////////////////////7

$("#fotoadicional-cantidad_foto").blur(function(){
    cantidad=$("#fotoadicional-cantidad_foto").val()
    monto =  $("#fotoadicional-monto").val();
    total= (cantidad)*(monto);
    $( "#fotoadicional-total" ).val(total);
    
});
//////////////////////////////////////////////////////////////////////////////////7

$("#fotoadicional-monto").blur(function(){
    cantidad=$("#fotoadicional-cantidad_foto").val()
    monto =  $("#fotoadicional-monto").val();
    total= (cantidad)*(monto);
    $( "#fotoadicional-total" ).val(total);
});

///////////////////////////////////////////////////////////////////////////////////////
});
</script>