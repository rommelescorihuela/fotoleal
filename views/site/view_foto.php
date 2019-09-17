<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Pago;
use app\models\Direccion;
/* @var $this yii\web\View */
/* @var $model app\models\Cliente */

$this->title = $model->id_cliente;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cliente-view">


    <p>
        <?php //echo Html::a('Update', ['update', 'id' => $model->id_cliente], ['class' => 'btn btn-primary']) ?>
        <?php //echo Html::a('Delete', ['delete', 'id' => $model->id_cliente], [
          //  'class' => 'btn btn-danger',
          //  'data' => [
            //    'confirm' => 'Are you sure you want to delete this item?',
          //      'method' => 'post',
          //  ],
      //  ]) ?>
    <h2>  Muchas gracias, tú pago se ha registrado satisfactoriamente.<br>
Te recomendamos estar atento a las indicaciones que te den los fotógrafos antes, <br>
durante y después de la ceremonia para una buena toma de tu fotografía<br></h2>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id_cliente',
            'cedula',
            'nombre',
            //'apellido',
            'correo',
            'telefono',
            'celular',
            //'id_direccion',
            //'id_programa',
             [
    'attribute' => 'Direccion',
    'format'    => 'html',
    'value'     => call_user_func(function($model)
    {
        $direccion = "";

        $modeldireccion=new Direccion();
        $direccion=$modeldireccion->findOne(['id_direccion'=>$model->id_direccion]);

        return $direccion->municipio.', '. $direccion->barrio.', '.$direccion->tipo_carrera.' '.$direccion->carrera1.'# '.$direccion->carrera2.'- '.$direccion->carrera3.' '.$direccion->tipo_casa.' '.$direccion->casa1.' '.$direccion->casa2;
    }, $model)
],
            [
    'attribute' => 'Pagos recibidos',
    'format'    => 'html',
    'value'     => call_user_func(function($model)
    {
        $items = "";
        /*foreach ($model->monto as $related) {
            //$items .= $related->monto."<br>";
            var_dump($model);
        }*/
        $modelpago=new Pago();
        $pagos=$modelpago->findAll(['id_cliente'=>$model->id_cliente]);
        foreach($pagos as $p)
        {
          $items .= "$".$p['monto']." En la fecha: ".$p['fecha_pago']."<br>";
        }
        return $items;
    }, $model)
],
        ],
    ]) ?>

</div>
<?php

$items="";
$modelpago=new Pago();
$pagos=$modelpago->findAll(['id_cliente'=>$model->id_cliente]);
foreach($pagos as $p)
{
  $items .= "$".$p['monto']." En la fecha: ".$p['fecha_pago']."<br>";
}

$tabla="<table border='1'>";

    $tabla.="<tr><td>nombre:</td>
                <td>cedula:</td>
                <td>Pagos recibidos</td></tr>
                <tr><td>$model->nombre $model->apellido</td>
                    <td>$model->cedula</td>
                    <td>$items</td></tr>";

$tabla.="</table>";

$numero=rand(10,100000000);
$path=Yii::getAlias('@webroot');
include $path.'/phpqrcode/qrlib.php';
$filename = $path.'/qr/test'.$numero.'.png';
$qr='../../qr/test'.$numero.'.png';
$qr1='test'.$numero.'.png';
$matrixPointSize = min(max((int)10, 1), 10);
//echo $numero=rand(10,100);
$info=$model->nombre.' '.$items;
QRcode::png($info, $filename, 'H', $matrixPointSize, 2);
echo '<img src="'.$qr.'" style=height: 300px;/><hr/>';

$content = "<p>Muchas gracias, tú pago se ha registrado satisfactoriamente.
Te recomendamos estar atento a las indicaciones que te den los fotógrafos antes, durante y después de la ceremonia para una buena toma de tu fotografía</p><br/>".$tabla ;
//$content .= "Se hace el envio de el link donde esta para descargar las imagenes " .$link[$q]. "</p>";
Yii::$app->mailer->compose("@app/mail/layouts/html", ["content" => $content])
->setFrom('soporteventas@fotografialeal.com')
->setTo($model->correo)
  ->setTo('rommelescorihuela@gmail.com')
->setSubject('comprobante de pago')
->attach(Yii::$app->request->hostInfo.'/fotoleal/web/qr/'.$qr1)
->send();
?>
<?= \dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]) ?>
       <?= Html::a('Volver', ['cliente/update1'], ['class'=>'btn btn-default']) ?>
