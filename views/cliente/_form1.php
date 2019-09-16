<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\Cliente;
use app\models\ClienteSearch;
/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */

//echo Yii::$app->request->hostInfo;

?>
<?= \dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]) ?>
<?php $form = ActiveForm::begin(); ?>

<div class="col-md-6" >
    <div class="col-md-12 " style="text-align:justify; font-size:15px;margin-top:-50px;">
        Por favor, registra tus datos con el fin de asegurar tu paquete de fotografías para la ceremonia.
<br>
    seleccione el paquete de su preferencia:
  </div>
  <div class="col-md-12 " style="text-align:justify; font-size:15px; border:1px solid black;">
  <br>



  <div id="banner" style="margin-top:0px;"><img src="../../../img/banner-fotos.gif"></div>
  <div class="col-md-12" style="font-size: 15px;margin-top:5%;">
    <b style="font-size: 20px; ">Paquete PREMIUM</b> &nbsp; &nbsp; &nbsp; &nbsp;Valor$140.000<br>
    </div>
    <div class="col-md-12" style="font-size: 13px;">
      Su paquete incluye:
      </div>


  <ul style="font-size: 13px;">
  <li>Hasta 20 fotografías digitales descargables dentro de los 4 días posteriores a la ceremonia de grado*</li>
  <li>9 fotografías impresas de 15 x 21 cm + ampliación de 20 x 30cm (de las mismas 20 fotografías digitales)**</li>
  <li>Album de lujo repujado con el escudo de la universidad</li>
  <li>Video de la ceremonia en HD</li>
</ul>

<div class="col-md-12" style="text-align:justify; font-size:70%">
  <b>*El link de descarga será enviado al correo suministrado en el momento de la compra
  <br>
  **El album con las fotografías impresas y el video serán enviados por correo certificado a la dirección suministrada dentro de los siguientes 14 días hábiles a la ceremonia.
  <br>
  ***Las fotografías impresas corresponden a: 
  <ul>
      <li>Una (1) Ampliación del graduando con el diploma abierto</li>
      <li>Dos (2) fotografías de la entrega del diploma</li>
      <li>Una (1) fotografía retrato</li>
      <li>Una (1) fotografía juramento dependiendo tu ubicación</li>
      <li>Fotos familiares</li>
  </ul>
  <br>
  *Fotografía Leal se permitirá seleccionar tus mejores fotos para hacer parte del material impreso.
  <br>
  *Fotografias adicionales tendrán un costo de $ 7.000 c/u</b>
</div>
<div class="col-md-1" style="margin-top:5%;">
<?= $form->field($model, 'paquete1')->checkbox(array('label'=>'','checked'=>'checked')); ?>
</div>
<div class="col-md-11" style="margin-top:5%;"><b>Selecciona este paquete</b></div>
</div>
<div class="col-md-12" style="height: 20px;"></div>
  <div class="col-md-12" style="text-align:justify; font-size:15px; border:1px solid black;">


  <div class="col-md-12" >
  <b style="font-size: 20px; ">Paquete PREMIUM DIGITAL</b>  &nbsp; &nbsp; &nbsp; &nbsp;Valor $110.000<br>
</div>
<div class="col-md-12" style="font-size: 13px;">
  Su paquete incluye:
  </div>
  <ul style="font-size: 13px;">
  <li>Hasta 20 fotografías digitales descargables dentro de los 4 días posteriores a la ceremonia de grado*
  Las fotografías serán de alta resolución con aviso de ceremonia de graduación.</li>
</ul>

<div class="col-md-12" style="text-align:justify; font-size:70%">
  <b>*El link de descarga será enviado al correo suministrado en el momento de la compra</b>
</div>
<div class="col-md-12" style="text-align:justify; font-size:70%; margin-top:2px;">
  <b>*El envío digital e impreso está sujeto a la cancelación total del paquete.</b><br>
  <b>Abono Mínimo $50.000</b>
</div>
<div class="col-md-1" style="margin-top:5%;">
<?= $form->field($model, 'paquete2')->checkbox(array('label'=>'')); ?>
</div>
<div class="col-md-11" style="margin-top:5%;"><b>Selecciona este paquete</b></div>

</div>
<div class="col-md-12" style="margin-top:5%;"><b>Dudas o inquietudes contáctenos: 3115008011 / 0312489868</b></div>
</div>
<div class="col-md-6" >
        <?= $form->field($model, 'id_cliente')->hiddenInput(['maxlength' => true])->label(false) ?>

<div class="col-md-2" ></div>
     <div class="col-md-10" >
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
    <div class="col-md-2"></div>
     <div class="col-md-10" >
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'disabled'=>true]) ?>
    </div>
    <div class="col-md-2" ></div>

    <?= $form->field($model, 'apellido')->hiddenInput(['maxlength' => true,'disabled'=>true])->label(false) ?>


    <div class="col-md-10" >
    <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2" ></div>
    <div class="col-md-10">
    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-10">
    <?= $form->field($model, 'celular')->textInput() ?>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-10">
    <?= $form->field($model, 'evento')->textInput(['disabled'=>true]) ?>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-10">
    <?= $form->field($model, 'programa')->textInput(['disabled'=>true]) ?>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-10">
    <?= $form->field($model, 'id_programa')->hiddenInput(['maxlength' => true])->label(false) ?>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-10">
    <?php echo $form->field($model, 'id_direccion')->hiddenInput()->label(false) ?>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-10"><h2>Por favor registre la direccion donde desea sea entregado su paquete de fotografias</h2>
    "no se permiten caracteres especiales"!"#$%&/()"</div>
    <div class="col-md-2"></div>
    <div class="col-md-5">
            <?= $form->field($modeldireccion, 'municipio')->textInput(); ?>
    </div>
    <div class="col-md-5">
            <?= $form->field($modeldireccion, 'barrio')->textInput() ?>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-4"><?php $var = [ 'Cl' => 'Cl', 'Cra' => 'Cra',  'Tvr' => 'Tvr','Dg' => 'Dg'];
      echo $form->field($modeldireccion, 'tipo_carrera')->dropDownList($var, ['prompt' => 'Seleccione' ])->label(false);?>
    </div>
    <div class="col-md-2">
            <?= $form->field($modeldireccion, 'carrera1')->textInput()->label(false) ?>
    </div>
    <div class="col-md-2" style="display:inline-flex">
            <?= "<span style='padding-left: 3%;padding-top: 2%;'>#</span>".$form->field($modeldireccion, 'carrera2')->textInput(['placeholder'=>'#'])->label(false) ?>
    </div>
    <div class="col-md-2" style="display:inline-flex">
            <?= "<span style='padding-left: 3%;padding-top: 2%;'>-</span>".$form->field($modeldireccion, 'carrera3')->textInput(['placeholder'=>'-'])->label(false) ?>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-4" ><?php $var = [ 'Casa' => 'Casa', 'Torre' => 'Torre',  'Apto' => 'Apto'];
      echo $form->field($modeldireccion, 'tipo_casa')->dropDownList($var, ['prompt' => 'Seleccione' ])->label(false);?>
</div>
    <div class="col-md-3">
    <?= $form->field($modeldireccion, 'casa1')->textInput()->label(false) ?>
    </div>
    <div class="col-md-3" style="display:inline-flex">
            <?= "<span style='padding-left: 0%;padding-top: 2%;'>Apto</span>".$form->field($modeldireccion, 'casa2')->textInput(['placeholder'=>'apto'])->label(false) ?>
    </div>



<div class="col-md-2" ></div>
    <div class="col-md-10" >
            <div id="combo"><?= $form->field($model, 'observaciones')->dropDownlist(['prompt'=>'Seleccione','0'=>'Primer abono','1'=>'Pago total']); ?></div>
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-10">
    <?php
    if(Yii::$app->user->isGuest)
    {
      echo $form->field($model, 'monto')->textInput(['maxlength' => true,'readonly'=>'readonly']);
    }
    else
      {
        echo $form->field($model, 'monto')->textInput(['maxlength' => true]) ;
      }

    ?>
    </div>
        <div class="col-md-2"></div>

    <div class="col-md-10" style="padding-left:2%">
      <?php Html::Button('Pagar electrónico', ['class' => 'btn btn-default', 'name' => 'buscar-button','id' => 'pagoe']) ?>
      <?php
      if(Yii::$app->user->isGuest)
      {
          echo Html::submitButton('Pagar electrónico', ['class' => 'btn btn-default', 'name' =>'electronico-button','id' => 'pagoefe1']);  
        //echo Html::Button('Pagar electrónico', ['class' => 'btn btn-default', 'name' => 'buscar-button','id' => 'pagoe']);
      }else {
        echo Html::submitButton('Pagar', ['class' => 'btn btn-default', 'name' => 'buscar-button','id' => 'pagoefe']);
        echo Html::Button('Enviar enlace', ['class' => 'btn btn-default', 'name' => 'buscar-button-sms','id' => 'pagosms']);
      }
      ?>
      </div>
    </div>
    <?php ActiveForm::end(); ?>
    <script>
      $(document).ready(function() {
      $("#pagoefe").click(function(event) {
          if( !confirm('Sr vendedor confirma que ha recibido el monto '+$("#cliente-monto").val()+' en efectivo ?') ){
              event.preventDefault();
          }

      });
  });
    </script>
    <script src='https://checkout.epayco.co/checkout.js'></script>
<script>
function getQueryParam(param) {
      location.search.substr(1)
        .split("&")
        .some(function(item) { // returns first occurence and stops
          return item.split("=")[0] == param && (param = item.split("=")[1])
        })
      return param
    }


    $(document).ready(function() {
         //llave publica del comercio
         //Referencia de payco que viene por url
         var ref_payco = getQueryParam('ref_payco');
         //Url Rest Metodo get, se pasa la llave y la ref_payco como paremetro
         var urlapp = "https://secure.epayco.co/validation/v1/reference/" + ref_payco;
         $.get(urlapp, function(response) {
           if (response.success) {
             if (response.data.x_cod_response == 1) {
               //Codigo personalizado
               alert("Transaccion Aprobada");
               //$("#w1").submit();
               console.log('transacción aceptada');
             }
             //Transaccion Rechazada
             if (response.data.x_cod_response == 2) {
               console.log('transacción rechazada');
             }
             //Transaccion Pendiente
             if (response.data.x_cod_response == 3) {
               console.log('transacción pendiente');
             }
             //Transaccion Fallida
             if (response.data.x_cod_response == 4) {
               console.log('transacción fallida');
             }

           } else {
             //alert("Error consultando la información");
           }
         });
       });

///////////////////////////pago electronico////////////////////////////////////
<?php if(Yii::$app->user->isGuest)
{
    $vendedor=2;
}
else
{
    $vendedor=Yii::$app->user->identity->id_usuario;
}
 ?>
/*$(document).ready(function(){
  $("#pagoe").click(function(){
    //alert('pago_electronico');
    if($("#cliente-paquete1").prop("checked")){
                //alert("Checkbox is checked.");
                paquete=1;
            }
            else
            {
              paquete=0;
            }
    var handler = ePayco.checkout.configure({
   				key: 'ae9dce5fff977ae8057e026e47f28a53',
   				test: false
   			})
          id_cliente=$("#cliente-id_cliente").val()+'**';
          correo=$("#cliente-correo").val()+'**';
          telefono=$("#cliente-telefono").val()+'**';
          celular=$("#cliente-celular").val()+'**';
          evento=$("#cliente-evento").val()+'**';
          programa=$("#cliente-programa").val()+'**';
          carrera1=$("#direccion-carrera1").val()+'**';
          carrera2=$("#direccion-carrera2").val()+'**';
          carrera3=$("#direccion-carrera3").val()+'**';
          municipio=$("#direccion-municipio").val()+'**';
          barrio=$("#direccion-barrio").val()+'**';
          casa1=$("#direccion-casa1").val()+'**';
          casa2=$("#direccion-casa2").val()+'**';
          monto=$("#cliente-monto").val()+'**';
          observaciones=$("#cliente-observaciones").val()+'**';
          tipo_carrera=$("#direccion-tipo_carrera").val()+'**';
          tipo_casa=$("#direccion-tipo_casa").val()+'**';
          vendedor =<?php echo $vendedor ?>;

        var data={
              //Parametros compra (obligatorio)
              name: 'Servicio de Fotografia',
              description: 'Servicio de Fotografia',
              invoice: <?php echo mt_rand(5000,999999) ?>,
              currency: "cop",
              amount: $("#cliente-monto").val(),
              country: "co",
              lang: "es",

              //Onpage="false" - Standard="true"
              external: "false",
              response: '<?php echo Yii::$app->request->hostInfo ?>'+"/fotoleal/web/index.php/cliente/respuesta?datos="+id_cliente+correo+telefono+celular+evento+programa+carrera1+carrera2+carrera3+casa1+casa2+municipio+barrio+monto+observaciones+tipo_carrera+tipo_casa+vendedor+'**'+paquete+'**',

              }
              handler.open(data)
              //console.log(d)
  });
});*/

$(document).ready(function(){
  $("#pagoe").click(function(){
    swal({
  title: "Importante",
  text: "Despues de realizada la transaccion no cierre el navegador, use el boton finalizar o espere el tiempo indicado y aguarde a que el sistema le indique que su pago fue registrado con exito",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    if($("#cliente-paquete1").prop("checked")){
                //alert("Checkbox is checked.");
                paquete=1;
            }
            else
            {
              paquete=0;
            }
    var handler = ePayco.checkout.configure({
   				key: 'ae9dce5fff977ae8057e026e47f28a53',
   				test: false
   			})
          id_cliente=$("#cliente-id_cliente").val()+'**';
          correo=$("#cliente-correo").val()+'**';
          telefono=$("#cliente-telefono").val()+'**';
          celular=$("#cliente-celular").val()+'**';
          evento=$("#cliente-evento").val()+'**';
          programa=$("#cliente-programa").val()+'**';
          carrera1=$("#direccion-carrera1").val()+'**';
          carrera2=$("#direccion-carrera2").val()+'**';
          carrera3=$("#direccion-carrera3").val()+'**';
          municipio=$("#direccion-municipio").val()+'**';
          barrio=$("#direccion-barrio").val()+'**';
          casa1=$("#direccion-casa1").val()+'**';
          casa2=$("#direccion-casa2").val()+'**';
          monto=$("#cliente-monto").val()+'**';
          observaciones=$("#cliente-observaciones").val()+'**';
          tipo_carrera=$("#direccion-tipo_carrera").val()+'**';
          tipo_casa=$("#direccion-tipo_casa").val()+'**';
          vendedor =<?php echo $vendedor ?>;

        var data={
              //Parametros compra (obligatorio)
              name: 'Servicio de Fotografia',
              description: 'Servicio de Fotografia',
              invoice: <?php echo mt_rand(5000,999999) ?>,
              currency: "cop",
              amount: $("#cliente-monto").val(),
              country: "co",
              lang: "es",

              //Onpage="false",
              //Standard="true",
              external: "false",
              response: '<?php echo Yii::$app->request->hostInfo ?>'+"/fotoleal/web/index.php/cliente/respuesta?datos="+id_cliente+correo+telefono+celular+evento+programa+carrera1+carrera2+carrera3+casa1+casa2+municipio+barrio+monto+observaciones+tipo_carrera+tipo_casa+vendedor+'**'+paquete+'**',

              }
              handler.open(data)
              //console.log(d)

  } else {

  }
});

  });
});
////////////////////////////rellenado automatico///////////////////////////////////////
$(document).ready(function(){
  $("#cliente-cedula").blur(function(){
      $.ajax({
       url: '<?php echo Yii::$app->request->baseUrl. '/index.php/cliente/update1' ?>',
       type: 'post',
       data: {
                 cedula: $("#cliente-cedula").val() ,
             },
       success: function (data) {
        $("#cliente-id_cliente").val(data['id_cliente']);
        $("#cliente-nombre").val(data['nombre']);
        $("#cliente-apellido").val(data['apellido']);
        $("#cliente-id_direccion").val(data['id_direccion']);
        $("#cliente-id_programa").val(data['id_programa']);
        $("#cliente-programa").val(data['programa']);
        $("#cliente-evento").val(data['evento']);
        $("#cliente-correo").val(data['correo']);
        $("#cliente-telefono").val(data['telefono']);
        $("#cliente-celular").val(data['celular']);
        $("#direccion-municipio").val(data['municipio']);
        $("#direccion-barrio").val(data['barrio']);
        $("#direccion-carrera1").val(data['carrera1']);
        $("#direccion-carrera2").val(data['carrera2']);
        $("#direccion-carrera3").val(data['carrera3']);
        $("#direccion-casa1").val(data['casa1']);
        $("#direccion-casa2").val(data['casa2']);
        $("#direccion-tipo_casa").val(data['tipo_casa']);
        $("#direccion-tipo_carrera").val(data['tipo_carrera']);
        //$("#cliente-monto").val(data['monto']);
        //alert(data['monto1'])
        if(data['npagos']>=2)
        {
          alert('Ud ya ha pagado la totalidad de su paquete');
          $("#pagoefe").attr('disabled',true);
          $("#pagosms").attr('disabled',true);
          $("#pagoefe1").attr('disabled',true);
        }
        else{
          if(data['npagos']==1)
          {
            if(data['observaciones']==1)
            {
              alert('Ud ya ha pagado la totalidad de su paquete');
              $("#pagoefe").attr('disabled',true);
              $("#pagosms").attr('disabled',true);
              $("#pagoefe1").attr('disabled',true);

            }
            else{
                $("#cliente-monto").val(data['monto']-data['monto1']);
                $("#cliente-observaciones").attr('disabled',true);
                //$("#cliente-observaciones").val(2)
                $("#combo").html('<select id="cliente-observaciones" class="form-control" name="Cliente[observaciones]" readonly="true" aria-required="true"><option value="2">Segundo abono</option></select>')
                $("#pagoefe1").attr('disabled',false);
            }
          }
          else
          {
            $("#pagoefe").attr('disabled',false);
            $("#pagosms").attr('disabled',false);
            $("#pagoefe1").attr('disabled',false);
          }

        }
          //console.log(data.search);
       }
  });
  });
});
$(document).ready(function(){
  $("#cliente-observaciones").change(function(){
    if($("#cliente-paquete1").prop("checked")){
                //alert("Checkbox is checked.");
                paquete=1;
            }
            else
            {
              paquete=0;
            }
      $.ajax({
       url: '<?php echo Yii::$app->request->baseUrl. '/index.php/cliente/consultamonto' ?>',
       type: 'post',
       data: {
                 tipo_monto: $("#cliente-observaciones").val() ,
                 id_programa: $("#cliente-id_programa").val(),
                 id_cliente:$("#cliente-id_cliente").val(),
                 paq:paquete,
             },
       success: function (data) {
        $("#cliente-monto").val(data['monto']);
        //alert(data['paquete']);
       }
  });
  });
});
$(document).ready(function(){
  $("#direccion-municipio").blur(function(){
        ba=$("#direccion-municipio").val();
        $("#direccion-municipio").val(ba.replace(/ /g,"_"));

  });
});
$(document).ready(function(){
  $("#direccion-barrio").blur(function(){
        ba=$("#direccion-barrio").val();
        $("#direccion-barrio").val(ba.replace(/ /g,"_"));

  });
});
$(document).ready(function(){
  $("#direccion-carrera1").blur(function(){
        ba=$("#direccion-carrera1").val();
        $("#direccion-carrera1").val(ba.replace(/ /g,"_"));

  });
});
$(document).ready(function(){
  $("#direccion-carrera2").blur(function(){
        ba=$("#direccion-carrera2").val();
        $("#direccion-carrera2").val(ba.replace(/ /g,"_"));

  });
});
$(document).ready(function(){
  $("#direccion-carrera3").blur(function(){
        ba=$("#direccion-carrera3").val();
        $("#direccion-carrera3").val(ba.replace(/ /g,"_"));

  });
});
$(document).ready(function(){
  $("#direccion-casa1").blur(function(){
        ba=$("#direccion-casa1").val();
        $("#direccion-casa1").val(ba.replace(/ /g,"_"));

  });
});
$(document).ready(function(){
  $("#direccion-casa2").blur(function(){
        ba=$("#direccion-casa2").val();
        $("#direccion-casa2").val(ba.replace(/ /g,"_"));

  });
});
///////////////////////////////pago sms////////////////////////////////////////
<?php if(Yii::$app->user->isGuest)
{
    $vendedor=2;
}
else
{
    $vendedor=Yii::$app->user->identity->id_usuario;
}
 ?>

      $(document).ready(function() {
      $("#pagosms").click(function(event) {
          if( confirm('Desea enviar en enlace de pago al cliente? ') ){
              event.preventDefault();
              if($("#cliente-paquete1").prop("checked")){
                //alert("Checkbox is checked.");
                paquete=1;
            }
            else
            {
              paquete=0;
            }
               id_cliente=$("#cliente-id_cliente").val()+'**';
          correo=$("#cliente-correo").val()+'**';
          correo1=$("#cliente-correo").val();
          telefono=$("#cliente-telefono").val()+'**';
          celular=$("#cliente-celular").val()+'**';
          evento=$("#cliente-evento").val()+'**';
          programa=$("#cliente-programa").val()+'**';
          carrera1=$("#direccion-carrera1").val()+'**';
          carrera2=$("#direccion-carrera2").val()+'**';
          carrera3=$("#direccion-carrera3").val()+'**';
          municipio=$("#direccion-municipio").val()+'**';
          barrio=$("#direccion-barrio").val()+'**';
          casa1=$("#direccion-casa1").val()+'**';
          casa2=$("#direccion-casa2").val()+'**';
          monto=$("#cliente-monto").val()+'**';
          observaciones=$("#cliente-observaciones").val()+'**';
          tipo_carrera=$("#direccion-tipo_carrera").val()+'**';
          tipo_casa=$("#direccion-tipo_casa").val()+'**';
          vendedor =<?php echo $vendedor ?>;
          url1= '<?php echo Yii::$app->request->hostInfo ?>'+"/fotoleal/web/index.php/cliente/update3?datos="+id_cliente+correo+telefono+celular+evento+programa+carrera1+carrera2+carrera3+casa1+casa2+municipio+barrio+monto+observaciones+tipo_carrera+tipo_casa+vendedor+'**'+paquete+'**';

       $.ajax({
        url: '<?php echo Yii::$app->request->baseUrl. '/index.php/cliente/sms' ?>',
        type: 'post',
        data: {
                  url: url1 ,
                  correo:correo1,
              },
                success: function (data) {
                alert('la información ha sido enviado exitosamente al correo escrito');
                location.reload();

                }
              });
          }

      });
  });

//////////////////////////////////////////////////////////////////////

$("#cliente-paquete1").click(function(){
  if($("#cliente-paquete1").prop("checked"))
  {
    $('#cliente-paquete2').prop('checked',false);
  }
  else
  {
    $('#cliente-paquete2').prop('checked',true);
  }
  $("#cliente-monto").val("");
  $("#cliente-observaciones").val("prompt");
});

$("#cliente-paquete2").click(function(){
  if($("#cliente-paquete2").prop("checked"))
  {
    $('#cliente-paquete1').prop('checked',false);
  }
  else
  {
    $('#cliente-paquete1').prop('checked',true);
  }
  $("#cliente-monto").val("");
  $("#cliente-observaciones").val("prompt");

});



</script>
