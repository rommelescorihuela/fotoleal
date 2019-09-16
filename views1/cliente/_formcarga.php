<?php

use yii\helpers\ArrayHelper;
use app\models\Evento;
use app\models\Programa;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cliente-form">

    <!--?php $form = ActiveForm::begin(); ?-->
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>


    <!-- Main content -->

      <div class="row">
        <!-- left column -->
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
          <!-- general form elements -->


              <h3 class="box-title" align="center">Carga de Cliente</h3>

            <!-- /.box-header -->
            <!-- form start -->


                <div class="form-group">


                 <!--?= $form->field($model, 'id_direccion')->textInput() ?-->

                <?=  $form->field($model, 'id_direccion')->hiddenInput(['value' => '0'])->label(false);?>
                <!--?= echo $form->field($model, 'id_direccion')->hiddenInput(['value' => '0'])->label(false);?-->




    <div class="cliente-form">

        <?= $form->field(new app\models\Evento(), 'id_evento')->dropDownList(
            ArrayHelper::map(\app\models\Evento::find()->all(), 'id_evento', 'nombre_evento'),
                        [
                            'prompt' => 'Seleccione',
                            'onchange'=>'
                            $.post( "../../index.php/programa/list?id='.'"+$(this).val(), function(data) {
                                $( "#'.Html::getInputId($model, 'id_programa').'" ).html( data );
                            });'

                    ]);
            echo $form->field($model, 'id_programa')->dropDownList(['prompt'=>'Seleccione'])
?>

    </div>
            <div class="form-group">
        <?= $form->field($model, 'carga')->fileInput() ?>
        <b>Nota: el archivo debe ser de extension Xls compatible con Excel 97 - 2003</b>
            </div>

                 <div class="form-group" align="center">
                 <?= Html::submitButton('cargar', ['class' => 'btn btn-success']) ?>
                 </div>

             <?php ActiveForm::end(); ?>
              </div>

        <div class="col-md-3">
        </div>

                 <br><br>
<div class="col-md-12" align="center">
  Archivo de prueba para validacion de Carga Masiva.
        </div>
<br>
<br>
<br>
<div class="col-md-12" align="center">

<a target="_blank" href=<?= Yii::$app->request->hostInfo?>/fotoleal/web/pdf/test.xls><img src=<?= Yii::$app->request->hostInfo?>/fotoleal/img/descarga.jpeg border="0"></a>
        </div>



</div>
