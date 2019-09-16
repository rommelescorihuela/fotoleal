<?php

use yii\helpers\ArrayHelper;
use app\models\Evento;
use app\models\Pago;
use app\models\Programa;
use app\models\Enlace;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\grid\GridView;
use yii\helpers\Url;
use dominus77\sweetalert2\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */

?>


<!--link rel="stylesheet" type="text/css" href="/css/loading.css"/-->
<!--link rel="stylesheet" type="text/css" href="loading-btn.css"/-->
<!--script language="JavaScript">
$("button").click(function() {
    var $btn = $(this);
    $btn.button('loading');
    // simulating a timeout
    setTimeout(function () {
        $btn.button('reset');
    }, 1000);
});
</script-->

   <script type="text/javascript">
      function checkSubmit() {

          document.getElementById("btsubmit").value = "Enviando...";
          document.getElementById("btsubmit").disabled = true;
          return true;
      }
     </script>

<?= \dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]) ?>

<script>
function enviar(boton)
{
      boton.disabled='disabled';
}
</script>

<div class="cliente-form">

    <!--?php $form = ActiveForm::begin(); ?-->



    <!-- Main content -->
  <div class="container-fluid">


      <div class="row">

        <div class="col-md-3">
        </div>

        <div class="col-md-6">
          <!-- general form elements -->


              <h3 class="box-title" align="center">Buscar Promocion</h3>

                <div class="form-group">


    <div class="cliente-form">
      <?php $form = ActiveForm::begin([
      "method" => "get",
      "action" => Url::toRoute("cliente/correo"),
       'options' => [
                'id' => 'form1'
             ],
      "enableClientValidation" => true,
    ]); ?>
    <?php $id=0;?>
        <?= $form->field(new app\models\Evento(), 'id_evento')->dropDownList(
            ArrayHelper::map(\app\models\Evento::find()->all(), 'id_evento', 'nombre_evento'),
                        [
                            'prompt'=>'Seleccion',
                            'onchange'=>'
                            $.post( "../../index.php/programa/list?id='.'"+$(this).val(), function(data) {
                                $( "#'.Html::getInputId($model, 'id_programa').'" ).html( data );
                            });'

                    ]);
            echo  $form->field($model, 'id_programa')->dropDownList(['0'=>'Seleccione'],
          ['options' =>
                    [
                      $id => ['selected' => true]
                    ]
          ])
?>

    </div>
                 <div class="form-group" align="center">
                 <?= Html::submitButton('Buscar', ['class' => 'btn btn-default']) ?>

                 </div>

             <?php ActiveForm::end(); ?>
              </div>
        <div class="col-md-3">
        </div>

</div>



<h3><?php //$search ?></h3>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<div class="container-fluid">


 <?php $forme = ActiveForm::begin([
      "method" => "post",
      "action" => Url::toRoute("cliente/correo"),
      'options' => [
                'id' => 'form2'
             ],
      "enableClientValidation" => true,
    ]); ?>

<h3 align="center" style="color:green;">Lista de Clientes Con Pagos</h3>
<!--table class="table table-bordered"   textInput-->


  <table class="table table-responsive-md table-bordered">
    <thead>
        <tr>
             <th>Selecione <?= $form->field($model, 'todo')->checkbox(array('label'=>'')); ?></th>
             <th>Cliente</th>
              <th>Pago</th>
             <th>Nombre</th>
             <th>Correo</th> </div>
             <th>Telefono</th>
             <th>Celular</th>
             <th>Programa</th>
             <th>Enlace</th>
             <th>Enviado</th>
         </tr>
    </thead>
    <?php
    $i=0;
    $arreglo=$model1;
    /*foreach($arreglo as $i)
    {
      echo $i['id_cliente'];
    }
    exit();*/
    $valor=0;
    foreach($arreglo as $j)
    { ?>
    <tbody>
        <tr>
          <?php $idcliente=$j['id_cliente']; ?>
         <td> <?= $forme->field($enlace, "valor[$i]")->checkbox(array('label'=>'','clase'=>'case')); ?></td>
           <td><?= $j['id_cliente'] ?>  <?=  $forme->field($enlace, "id[$i]")->hiddenInput(['value' => $j['id_cliente']])->label(false);?> </td>
           <td> <?= $j['tipo_pago']?></td>
           <td><?= $j['nombre'] ?> <?=  $forme->field($enlace, "nombre[$i]")->hiddenInput(['value' => $j['nombre']])->label(false);?></td>
           <td><?= $j['correo'] ?> <?=  $forme->field($enlace, "correo[$i]")->hiddenInput(['value' => $j['correo']])->label(false);?></td>
           <td><?= $j['telefono'] ?></td>
           <td><?= $j['celular'] ?></td>
           <?php
           $programavista = new Programa();
           $programavista = Programa::find()->where(['id_programa' => $j['id_programa']])->one();
          // var_dump($programavista);?>
           <td><?= $programavista->nombre_programa ?></td>
           <?php
           $prueba = new Enlace();
           $prueba = Enlace::find()->where(['id_cliente' => $j['id_cliente']])->one();
           ?>
           <?php if($prueba != NULL)
           {?>
            <td><?= $forme->field($enlace, "link[$i]")->textInput(['value' => $prueba->enlace])->label(false); ?></td>
      <?php
            }
           else
           { ?>
           <td><?= $forme->field($enlace, "link[$i]")->textInput()->label(false); ?></td>
         <?php
         }
         ?>
         <?php if($prueba['enviado'] == 1)
           {?>
            <td align="center" style=" color: blue;" >Correo Enviado</td>
         <?php
            }
           else
           { ?>
            <td align="center" style=" color: orange;">Correo no Enviado</td>
            <?php
             }
            ?>
           </tr>
    <?php
    $valor++;
    $i++;
  } ?>

    </tbody>
</table>
 <div class="form-group" align="center">


         <?php   echo $forme->field($enlace, "accion")->radioList( [1=>'GUARDAR', 2 => 'ENVIAR'] )->label(false); ?>



                 </div>



          <div class="form-group" align="center">




                <?= Html::submitButton('Aplicar', [
                    'class' =>'btn btn-default',
                    'data' => [
                        'confirm' => 'Desea Aplicar Los Cambios?'
                        ]
                    ]) ?>

             <!--?php   echo Html::beginForm( Yii::app()->createUrl('cliente/correo1'),'post'); ?-->

                <!--?php echo Html::submitButton('Enviar Correo', array('name' => 'button1','class'=>'btn btn-default')); ?-->



                <!--?php echo Html::submitButton('Guardar', array('name' => 'button2','class'=>'btn btn-default')); ?-->


                 </div>



 <?php ActiveForm::end(); ?>
</div>

<br><br>

    <div class="container-fluid">

        <h3 align="center" style="color:orange;">Lista de Clientes con abono</h3>
<!--table class="table table-bordered"   textInput-->


<?php $forme = ActiveForm::begin([
     "method" => "post",
     "action" => Url::toRoute("cliente/recordatorio"),
     'options' => [
               'id' => 'form3'
            ],
     "enableClientValidation" => true,
   ]); ?>

  <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
    <thead>
        <tr>
             <th>Cliente</th>
             <th>Nombre</th>
             <th>Correo</th>
             <th>Telefono</th>
             <th>Celular</th>
             <th>Programa</th>
             <th>Abono</th>
         </tr>
    </thead>
    <?php
    $i=0;
    $arreglo3=$model4;
    /*foreach($arreglo as $i)
    {
      echo $i['id_cliente'];
    }
    exit();*/
    if($arreglo3!='')
    {
      $rc=1;
    foreach($arreglo3 as $k)
    { ?>
    <tbody>
        <tr>
          <?php $idclienten=$k['id_cliente']; ?>
           <td><?= $k['id_cliente'] ?></td>
           <td><?= $k['nombre'] ?> </td>
           <td><?= $k['correo'] ?> <?php echo  Html::hiddenInput('correo'.$rc,$k['correo']);?></td>
           <td><?= $k['telefono'] ?></td>
           <td><?= $k['celular'] ?></td>
           <?php
           $rc++;
           $programavista3 = new Programa();
           $programavista3 = Programa::find()->where(['id_programa' => $k['id_programa']])->one();
          // var_dump($prueba) $k['id_programa']?>
           <td><?= $programavista3->nombre_programa ?></td>
           <?php
           $pagovista3 = new Pago();
           $pagovista3 = Pago::find()->where(['id_cliente' => $k['id_cliente']])->one();
          // var_dump($pagovista2) ?>
           <?php if($pagovista3 == NULL)
           {?>
           <td> No Existe Pago </td>
           <?php
           }?>
         <?php if($pagovista3 != NULL) { ?>
           <td> <?= $pagovista3->monto?></td>
         <?php } ?>
    <?php
    $k++;
    $i++;
  }
}
  ?>
  <?= Html::submitButton('recordatorio', [
      'class' =>'btn btn-default',
      'data' => [
          'confirm' => 'Desea enviar el recordatorio a todos los correos?'
          ]
      ]) ?>
    </tbody>
</table>
    </div>
<?php ActiveForm::end(); ?>
    <div class="container-fluid">

        <h3 align="center" style="color:red;">Lista de Clientes Sin Pago</h3>
<!--table class="table table-bordered"   textInput-->




  <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
    <thead>
        <tr>
             <th>Cliente</th>
             <th>Nombre</th>
             <th>Correo</th>
             <th>Telefono</th>
             <th>Celular</th>
             <th>Programa</th>
             <th>Status Pago</th>
         </tr>
    </thead>
    <?php
    $i=0;
    $arreglo2=$model3;
    /*foreach($arreglo as $i)
    {
      echo $i['id_cliente'];
    }
    exit();*/
    if($arreglo2!='')
    {


    foreach($arreglo2 as $k)
    { ?>
    <tbody>
        <tr>
          <?php $idclienten=$k['id_cliente']; ?>
           <td><?= $k['id_cliente'] ?>  </td>
           <td><?= $k['nombre'] ?> </td>
           <td><?= $k['correo'] ?> </td>
           <td><?= $k['telefono'] ?></td>
           <td><?= $k['celular'] ?></td>
           <?php
           $programavista2 = new Programa();
           $programavista2 = Programa::find()->where(['id_programa' => $k['id_programa']])->one();
           //var_dump($programavista2['nombre_programa']); exit();?>
           <td><?= $programavista2['nombre_programa'] ?></td>
           <?php
           $pagovista2 = new Pago();
           $pagovista2 = Pago::find()->where(['id_cliente' => $k['id_cliente']])->one();
          // var_dump($pagovista2) ?>
           <?php if($pagovista2 == NULL)
           {?>
           <td> No Existe Pago </td>
           <?php
           }?>
    <?php
    $k++;
    $i++;
  }
  }
  ?>

    </tbody>
</table>
    </div>

  </div>
  <script>
    k=<?= count($arreglo) ?>;
$("#cliente-todo").on("click", function() {
  for (i=0;i<k;i++) {

    $("#enlace-valor-"+i).prop("checked", this.checked);
   }
});

// if all checkbox are selected, check the selectall checkbox and viceversa
$(".case").on("click", function() {
  if ($(".case").length == $(".case:checked").length) {
    $("#selectall").prop("checked", true);
  } else {
    $("#selectall").prop("checked", false);
  }
});
</script>
