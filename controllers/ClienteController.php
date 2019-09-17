<?php
namespace app\controllers;
ob_start();
use Yii;
use app\models\ReporteForm;
use app\models\ImportarForm;
use app\models\Cliente;
use app\models\ClienteSearch;
use app\models\Programa;
use app\models\ProgramaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
//use yii\bootstrap\Alert;
use app\models\FormSearch;
use yii\helpers\Html;
use app\models\Pago;
use app\models\Direccion;
use app\models\Evento;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use dominus77\sweetalert2\Alert;

use app\models\Enlace;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use linslin\yii2\curl;
use yii\data\ArrayDataProvider;
use PhpOffice\PhpSpreadsheet\IOFactory;
$path=Yii::getAlias('@webroot').'/phpqrcode/qrlib.php';
include $path;
use QRcode;

//use app\web\phpqrcode;
/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class ClienteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Cliente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel1 = new ClienteSearch();
        $dataProvider = $searchModel1->search1(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel1' => $searchModel1,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionAsistencia($id)
{
  $model=new Cliente();
 if(Yii::$app->request->post())
  {
    $asis=Yii::$app->request->post();
    $model = $this->findModel($id);
    //$modeldireccion=$this->findModelDireccion($model->id_direccion);
    $model->asistencia=$asis['Cliente']['asistencia'];
    $model->save();

  }
  return $this->render('asistencia', [
    'model'=>$model,

  ]);
}

    /**
     * Displays a single Cliente model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionView1($id)
    {
        return $this->render('view1', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionConsultaestado()
    {

        $model = new Pago();
        $cliente= new Cliente();
        $pago = $model->find()->where(['confirmacion'=>3])->all();
        //var_dump($pago);
        foreach ($pago as $k)
        {
          $ref=$k['ref_payco_corto'];
            //$ref='9453187';
          $curl = new curl\Curl();
          //$response = $curl->get("https://secure.epayco.co/validation/v1/reference/".$ref);
          $response = $curl->get("https://secure.payco.co/pasarela/estadotransaccion?id_transaccion=".$ref);
          $respuesta=json_decode($response,true);
          //echo $ref;
          //var_dump($respuesta["success"]);
          if($respuesta["success"]==true)
          {
              if($respuesta['data']['x_cod_respuesta']==1)
              {
                $factura=$respuesta['data']['x_id_factura'];
                $model1 = new Pago();
                $actp = $model1->findOne($k['id_pago']);
                $actp->confirmacion=1;
                $actp->factura=$factura;
                $actp->save();
                $id_cliente=$actp->id_cliente;
                $cli = $cliente->find()->where(['id_cliente'=>$id_cliente])->one();
                $items="";
                $modelpago=new Pago();
                $pagos=$modelpago->findAll(['id_cliente'=>$id_cliente]);
                foreach($pagos as $p)
                {
                    $items .= "$".$p['monto']." En la fecha: ".$p['fecha_pago']."<br>";
                }
                $tabla="<table border='1'>";

                $tabla.="<tr><td>nombre:</td>
                    <td>cedula:</td>
                    <td>Pagos recibidos</td></tr>
                    <tr><td>$cli->nombre $cli->apellido</td>
                        <td>$cli->cedula</td>
                        <td>$items</td></tr>";

                $tabla.="</table>";

                $numero=rand(10,100000000);
                $path=Yii::getAlias('@webroot');
                $filename = $path.'/qr/test'.$numero.'.png';
                $qr='../../qr/test'.$numero.'.png';
                $qr1='test'.$numero.'.png';
                $matrixPointSize = min(max((int)10, 1), 10);
                $info=$cli->nombre.' '.$items;
                QRcode::png($info, $filename, 'H', $matrixPointSize, 2);
                //echo '<img src="'.$qr.'" style=height: 300px;/><hr/>';

                $content = "<p>Muchas gracias, tú pago se ha registrado satisfactoriamente.
                Te recomendamos estar atento a las indicaciones que te den los fotógrafos antes, durante y después de la ceremonia para una buena toma de tu fotografía</p><br/>".$tabla ;
                Yii::$app->mailer->compose("@app/mail/layouts/html", ["content" => $content])
                ->setFrom('soporteventas@fotografialeal.com')
                ->setTo($cli->correo)
                ->setSubject('comprobante de pago')
                ->attach(Yii::$app->request->hostInfo.'/fotoleal/web/qr/'.$qr1)
                ->send();
                

              }

          }
        }  
         Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, 'Se han actualizado los pagos pendientes que cambiaron a aprobado');
        return $this->redirect('../site/admin');

    }
       public function actionCreate()
    {
       $model = new Cliente();

       if ($model->load(Yii::$app->request->post()))
         {
            $programa=$model->id_programa;
            $uploadFile = UploadedFile::getInstance($model, 'carga');
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($uploadFile->tempName);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            //echo count($sheetData);


            $valida=0;
            $valida2=0;
            $valida3=0;


            $e=0;
            for($e=1;$e<=(count($sheetData));$e++)
            {


            if(preg_match("/^[0-9]+$/",$sheetData[$e]['B']))
            {
              $r=1;
                //var_dump("numero1");
            } else{
                //var_dump("numeo2");
               $r=2;
            }

            //if(preg_match("/^[a-zA-Z]+$/", $sheetData[$e]['C']))
            if(preg_match("/^[0-9]+$/",$sheetData[$e]['C']))
            {
                $t=2;
                //var_dump("numero1");
            } else{
                //var_dump("numeo2");
               $t=1;
            }

            /*if(preg_match("/^[a-zA-Z]+$/", $sheetData[$e]['D']))
            {
                $j=1;
                //var_dump("numero1");
            } else{
                //var_dump("numeo2");
               $j=2;
            }*/

            if($r==2)
            {
            $valida=2;
            }
            if($t==2)
            {
            $valida2=2;
            }
            /*if($j==2)
            {
            $valida3=2;
            }*/

            }

//var_dump($valida);
//var_dump($valida2);
//var_dump($valida3);

            if($valida==0 && $valida2==0)
            {
            //  var_dump('bueno');
            //  die();
               $i=0;
           for($i=1;$i<=(count($sheetData));$i++)
            {

            //var_dump($sheetData[$i]);
            echo $model->cedula=$sheetData[$i]['B'];
            $model->nombre=$sheetData[$i]['C'];
            //$model->apellido=$sheetData[$i]['D'];
            $model->id_direccion=0;
            $model->asistencia=0;
            $model->id_programa=$programa;
            $model->save(false);
            //var_dump($model->save(false));
            //var_dump($model->getErrors());
            $model = new Cliente();

            }
                      Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                        [
                           'title' => 'Carga de Datos exitosa',
                            'text' => 'Su Informacion se Guardo con exito!!',
                            'confirmButtonText' => 'Aceptar!',
                            'timer' => 4000,
                        ]
                        ]);

            return $this->redirect(['index']);

            }
            else
            {
            //  var_dump('malo');
            //  die();
              Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_ERROR, [
                        [
                            'title' => 'Error en la carga de archivo',
                            'text' => 'Se debe colocar bien el formato xls',
                            'confirmButtonText' => 'Aceptar!',
                            'timer' => 4000,
                        ]
                     ]);
              return $this->redirect(['index']);
              // die();
            }

        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionRecordatorio()
    {
      $id_clientes=Yii::$app->request->post();
      echo $rc=count($id_clientes);
      for ($i=1; $i <= $rc ; $i++) {
      $content = "Cordial saludo te da Fotografia leal,<br />
Te recordamos  realizar el pago restante del paquete adquirido en la ceremonia de graduación en el siguiente enlace: http://www.fotografialeal.com/fotoleal/web/index.php/cliente/update1 ,
para poder realizar el envío de tus fotografías lo mas pronto posible.<br />
Gracias, ";
      Yii::$app->mailer->compose("@app/mail/layouts/html", ["content" => $content])
      ->setFrom('soporteventas@fotografialeal.com')
      ->setTo($id_clientes['correo'.$i])
      //->setBcc ('rommelescorihuela@gmail.com')
      ->setSubject('Recordatorio de pago')
      ->send();
      if(($i % 25)==0)
      {
          echo $i;
          Yii::$app->mailer->getTransport()->stop();
          sleep(10);
          //Yii::$app->mailer->getTransport()->start();
          //sleep(10);
      }
      }
      Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                [
                    'title' => 'Recordatorio enviado',
                    'text' => 'El recordatorio se ha enviado a todos los correos',
                    'confirmButtonText' => 'Aceptar!',
                    'timer' => 4000,
                ]
             ]);
      return $this->redirect(['index']);
    }
    /**
     * Creates a new Cliente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate1()
    {
        $model = new Cliente();
        $modeldireccion=new Direccion();

        if ($model->load(Yii::$app->request->post()) && $modeldireccion->load(Yii::$app->request->post()))
        {
            $modeldireccion->save();
            $model->id_direccion= $modeldireccion->id_direccion;
            $model->asistencia=0;
            $model->save();
            return $this->redirect(['view1', 'id' => $model->id_cliente]);
        }

        return $this->render('create1', [
            'model' => $model,
            'modeldireccion' => $modeldireccion,
        ]);
    }

    /**
     * Updates an existing Cliente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->id_direccion==0)
        {$modeldireccion= new Direccion();}
        else
        {$modeldireccion=$this->findModelDireccion($model->id_direccion);}

        //$modeldireccion=$this->findModelDireccion($model->id_direccion);
        //var_dump($model->id_direccion);
        //var_dump($modeldireccion);
        //exit();
       if ($model->load(Yii::$app->request->post()) && $modeldireccion->load(Yii::$app->request->post()))
        {
            $modeldireccion->save();
            $model->id_direccion= $modeldireccion->id_direccion;
            $model->save();
            //var_dump($model->errors);
            //var_dump($model);
            //exit();
            return $this->redirect(['view', 'id' => $model->id_cliente]);
        }

        return $this->render('update', [
            'model' => $model,
            'modeldireccion' => $modeldireccion,
        ]);
    }

public function actionUpdate1()
    {
        $model = new Cliente(['scenario' => 'principal']);
        $modelevento= new Evento();
        $modeldireccion=new Direccion(['scenario' => 'principal']);
        if (Yii::$app->request->isAjax) {
        $cedula = Yii::$app->request->post();
        $ci=$cedula['cedula'];
        $cliente=$model->find()
        ->joinWith('programa')
        ->innerjoin("evento","evento.id_evento=programa.id_evento")
        ->where(['cedula'=>$ci])
        ->andWhere(['evento.cerrado'=>'0'])
        ->one();
        foreach ($cliente->getPrograma()->all() as $k) {
            $id_programa=$k['id_programa'];
            $nombre_programa=$k['nombre_programa'];
            $id_evento=$k['id_evento'];
        }
        $evento=$modelevento->find()->where(['id_evento'=>$id_evento])->one();
        $modelpago1 =new Pago();
        $npagos=$modelpago1->find()->where(['id_cliente'=>$cliente->id_cliente,'confirmacion'=>'1'])->all();
        $direccion = $modeldireccion->findOne($cliente->id_direccion);

        //var_dump($direccion);
        //exit();
        if(count($npagos)>0)
        {

        foreach ($npagos as $np) {
            $observaciones=$np['observaciones'];
            $monto1=$np['monto'];
        }
        }
        else {
          $observaciones='';
          $monto='';
          $monto1='';
        }
        if(isset($direccion))
        {
        $municipio = $direccion->municipio;
        $barrio = $direccion->barrio;
        $carrera1 = $direccion->carrera1;
        $carrera2 = $direccion->carrera2;
        $carrera3 = $direccion->carrera3;
        $casa1 = $direccion->casa1;
        $casa2 = $direccion->casa2;
        $tipo_casa = $direccion->tipo_casa;
        $tipo_carrera = $direccion->tipo_carrera;
        }
        else
        {
        $municipio = '';
        $barrio ='';
        $carrera1 ='';
        $carrera2 = '';
        $carrera3 = '';
        $casa1 = '';
        $casa2 = '';
        $tipo_casa = '';
        $tipo_carrera = '';

        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
        'cedula'=> $ci,
        'id_cliente' => $cliente->id_cliente,
        'id_direccion' => $cliente->id_direccion,
        'nombre'=> $cliente->nombre,
        'apellido' => $cliente->apellido,
        'programa' => str_replace(' ', '', $nombre_programa),
        'correo' => $cliente->correo,
        'telefono' => $cliente->telefono,
        'celular' => $cliente->celular,
        'id_programa' => $id_programa,
        'evento' =>str_replace(' ', '', $evento['nombre_evento']),
        'monto' =>$evento['monto_evento'],
        'npagos' =>count($npagos),
        'observaciones' => $observaciones,
        'monto1' => $monto1,
        'municipio' => $municipio,
        'barrio' => $barrio,
        'carrera1' => $carrera1,
        'carrera2' => $carrera2,
        'carrera3' => $carrera3,
        'casa1' => $casa1,
        'casa2' => $casa2,
        'tipo_casa' => $tipo_casa,
        'tipo_carrera' => $tipo_carrera,
            ];
        }
        if(Yii::$app->request->post())
        {
            $post=Yii::$app->request->post();
            //var_dump($post);
            $id=$post['Cliente']['id_cliente'];
            $paquete=$post['Cliente']['paquete1'];
            //echo $paquete;
            //exit();
            $model = $this->findModel($id);

        if($model->id_direccion==0)
        {
            $modeldireccion=new Direccion();
        }
        else
        {
            $modeldireccion=$this->findModelDireccion($model->id_direccion);
        }

       if ($model->load(Yii::$app->request->post()) && $modeldireccion->load(Yii::$app->request->post()))
        {
            $modeldireccion->save();
            $model->id_direccion= $modeldireccion->id_direccion;
            $model->save();
            //var_dump($model->errors);
            //exit();
            //var_dump($model);
            //var_dump(Yii::$app->request->post());
            $modelpago=new Pago();
            $modelpago->paquete=$paquete;
            $modelpago->numero_referencia=mt_rand(5000,999999);
            $modelpago->monto=$model->monto;
            $modelpago->id_cliente=$model->id_cliente;
            $modelpago->observaciones=$model->observaciones;
            $modelpago->fecha_pago=date('Y-m-d');

            $buscapago = new  Pago;
            $bp= $buscapago->find()->where(['id_cliente'=>$model->id_cliente])->all();
            if(count($bp)>0)
            {
                foreach ($bp as $k) {
                    $id_usuario=$k['id_usuario'];
                }
                $modelpago->id_usuario=$id_usuario;
            }
            else
            {
                if(Yii::$app->user->isGuest)
                {
                    $modelpago->id_usuario=2;
                }
                else
                {
                    $modelpago->id_usuario=Yii::$app->user->identity->id_usuario;
                }
            }

            $modelpago->tipo_pago='efectivo';
            $modelpago->ref_payco='0';
            $modelpago->confirmacion='1';
            $modelpago->factura=$modelpago->numero_referencia;
            $modelpago->save();
            if(isset($post['electronico-button']))
            {
              $modelpago->confirmacion='0';
              $modelpago->tipo_pago='electronico';
              $modelpago->save();
              return $this->render('pagoepayco', [
                  'modelpago' => $modelpago,
              ]);
            }
            $items="";
            $modelpago=new Pago();
            $pagos=$modelpago->findAll(['id_cliente'=>$model->id_cliente]);
            foreach($pagos as $p)
            {
              $items .= "$".$p['monto']." En la fecha: ".$p['fecha_pago']."<br>";
            }
            Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, 'Muchas gracias, tú pago se ha registrado satisfactoriamente.
Te recomendamos estar atento a las indicaciones que te den los fotógrafos antes, durante y después de la ceremonia para una buena toma de tu fotografía');

            return $this->redirect(['view', 'id' => $model->id_cliente]);

             ///////////////////////////////////////////////////////////////////////////////////////////////
             return $this->render('update1', [
                 'model' => $model,
                 'modeldireccion' => $modeldireccion,
                 'items' => $items,
             ]);
        }
        }

        return $this->render('update1', [
            'model' => $model,
            'modeldireccion' => $modeldireccion,
        ]);
    }

    public function actionRespuesta($datos)
    {
        $d=explode('**',$datos);
        $ref_payco=explode('=',$d[19]);

        return $this->render('respuesta',[
            'ref_payco'=>$ref_payco[1],
            'datos' => $datos,
        ]);
    }
        public function actionRespuesta2($id,$ref_payco)
    {
        $pago=new Pago();
        $p=$pago->find()->where(['id_pago'=>$id])->one();
        $curl = new curl\Curl();
        $response = $curl->get("https://secure.epayco.co/validation/v1/reference/".$ref_payco);
        $respuesta=json_decode($response,true);
        //var_dump($respuesta);
        if(isset($respuesta))
        {
          $ref_payco_corto=$respuesta['data']['x_ref_payco'];
          $confirmacion=$respuesta['data']['x_cod_response'];
          $p->ref_payco_corto=$ref_payco_corto;
        }
        else
        {
          $confirmacion=0;
        }
        $p->confirmacion=$confirmacion;
        $p->ref_payco=$ref_payco;
        $p->tipo_pago='electronico';
        $p->save();
        /*return $this->render('respuesta',[
            'ref_payco'=>$ref_payco[1],
            'datos' => $datos,
        ]);*/
        return $this->redirect('update1');
    }
    public function actionUpdate2($datos)
    {

      $model = new Cliente();
      $modelevento= new Evento();
      $modeldireccion=new Direccion();
       $d=explode('**',$datos);
       $ref_payco=explode('?',$d[19]);
       //var_dump($d);
       //echo $ref_payco[1];
      $ref_payco1=explode('=',$ref_payco[1]);
      //echo $ref_payco1[1];
      $confirmacion=explode('=',$ref_payco[2]);
      $factura=explode('=',$ref_payco[3]);
      //var_dump($ref_payco);
       //exit();

      $id=$d[0];
      //$vendedor=$d[17];
      if(isset($d[17]))
      {
        $vendedor=$d[17];
      }
      else {
        $vendedor=2;
      }
      $model = $this->findModel($id);

        if($model->id_direccion==0)
        {
            $modeldireccion=new Direccion();
        }
        else
        {
            $modeldireccion=$this->findModelDireccion($model->id_direccion);
        }
        $modeldireccion->municipio=$d[11];
        $modeldireccion->barrio=$d[12];
        $modeldireccion->casa1=$d[9];
        $modeldireccion->casa2=$d[10];
        $modeldireccion->carrera1=$d[6];
        $modeldireccion->carrera2=$d[7];
        $modeldireccion->carrera3=$d[8];
        $modeldireccion->tipo_carrera=$d[15];
        $modeldireccion->tipo_casa=$d[16];
        $modeldireccion->save();
        //var_dump($modeldireccion->errors);
        //exit();
        $model->id_direccion= $modeldireccion->id_direccion;
        $model->correo=$d[1];
        $model->telefono=$d[2];
        $model->celular=$d[3];


        $model->save();
        $modelpago=new Pago();
        $modelpago->numero_referencia=mt_rand(5000,999999);
        $modelpago->monto=$d[13];
        $modelpago->ref_payco=$ref_payco1[1];
        $modelpago->confirmacion=$confirmacion[1];
        $modelpago->factura=$factura[1];
        $modelpago->paquete=$d[18];
        $modelpago->id_cliente=$model->id_cliente;
        $modelpago->observaciones=$d[14];
        $modelpago->fecha_pago=date('Y-m-d');
        $buscapago = new  Pago;
            $bp= $buscapago->find()->where(['id_cliente'=>$model->id_cliente])->all();
            if(count($bp)>0)
            {
                foreach ($bp as $k) {
                    $id_usuario=$k['id_usuario'];

                }
                $modelpago->id_usuario=$id_usuario;
            }
            else
            {
              $modelpago->id_usuario=$vendedor;
                /*if($vendedor)
                {
                    $modelpago->id_usuario=2;
                }
                else
                {
                    $modelpago->id_usuario=Yii::$app->user->identity->id_usuario;
                }*/
            }

        $modelpago->tipo_pago='electronico';
        $modelpago->save();
        //var_dump($model->errors);
          //var_dump($modeldireccion->errors);
            //var_dump($modelpago->errors);
        //exit();
       if($confirmacion[1]==1)
       {

        Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, 'El recibo de pago ha sido enviado a su correo');


        return $this->redirect(['view', 'id' => $model->id_cliente]);
       }
       else
       {
            Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, 'Su pago se esta procesando, en breve le sera enviado el recibo de pago a su correo');
            /*$model = new Cliente(['scenario' => 'principal']);
            $modelevento= new Evento();
            $modeldireccion=new Direccion(['scenario' => 'principal']);
            return $this->render('update1', [
            'model' => $model,
            'modeldireccion' => $modeldireccion,
        ]);*/
        return $this->redirect(['update1']);
       }

        //var_dump ($model);
    }
      public function actionUpdate3($datos)
    {
        $d=explode('**',$datos);
        //var_dump($d);
        $municipio=$d[11]."**";
        $barrio=$d[12]."**";
        $casa1=$d[9]."**";
        $casa2=$d[10]."**";
        $carrera1=$d[6]."**";
        $carrera2=$d[7]."**";
        $carrera3=$d[8]."**";
        $id_cliente=$d[0]."**";
        $correo=$d[1]."**";
        $telefono=$d[2]."**";
        $celular=$d[3]."**";
        $monto=$d[13];
        $observaciones=$d[14];
        $tipo_carrera=$d[15];
       // $tipo_casa=$d[16];
        //$url="http://localhost/fotoleal/web/index.php/cliente/update2?datos=".$id_cliente.$correo.$telefono.$celular.$evento.$programa.$carrera1.$carrera2.$carrera3.$casa1.$casa2.$municipio.$barrio.$monto.$observaciones"
        $url=Yii::$app->request->hostInfo."/fotoleal/web/index.php/cliente/respuesta?datos=".$datos;
        //echo $url;
      $enlace=  "<form id='boton-epayco'>
    <script src='https://checkout.epayco.co/checkout.js'
        data-epayco-key='ae9dce5fff977ae8057e026e47f28a53'
        class='epayco-button'
        data-epayco-amount=".$monto."
        data-epayco-name='Servicio de Fotografia'
        data-epayco-description='Servicio de Fotografia'
        data-epayco-currency='COP'
        data-epayco-country='CO'
        data-epayco-test='false'
        data-epayco-external='false'
        data-epayco-response=".$url."
        data-epayco-confirmation=''
        data-epayco-button='https://369969691f476073508a-60bf0867add971908d4f26a64519c2aa.ssl.cf5.rackcdn.com/btns/boton_carro_de_compras_epayco2.png'>
    </script>
</form>
<img src='../../icono/epaycologo.png' id='logoepayco'>
";
    return $this->render('pago', [
            'enlace' => $enlace,

        ]);
    }
      public function actionSms()
    {
      if(Yii::$app->request->post())
      {
          $post=Yii::$app->request->post();
          //$id=$post['Cliente']['id_cliente'];



      $content = "<p>Como lo solicito se envia el enlace de pago</p><br/>".$post['url'] ;

        Yii::$app->mailer->compose("@app/mail/layouts/html", ["content" => $content])
        ->setFrom('soporteventas@fotografialeal.com')
        ->setTo($post['correo'])
        //  ->setTo('rommelescorihuela@gmail.com')
        ->setSubject('enlace de pago')
        ->send();
      }
    }

    public function actionConsultamonto()
    {
        if(Yii::$app->request->post())
      {
          $post=Yii::$app->request->post();
          //$id=$post['Cliente']['id_cliente'];
            $paquete=$post['paq'];
            $id_cliente=$post['id_cliente'];
            $modelpago= new Pago();
            $pago=$modelpago->find()->where(['id_cliente'=>$id_cliente])->sum('monto');
            //var_dump(intval($pago));
            $id_programa=$post['id_programa'];
            $tipo_monto=$post['tipo_monto'];
            $prog=new Programa();
            $programa=$prog->findOne(['id_programa' => $id_programa]);
            $evento=new Evento();
            $eve=$evento->findOne(['id_evento' => $programa['id_evento']]);
            if($tipo_monto==0)
            {
              if(!Yii::$app->user->isGuest)
              {
                  $monto='';
              }
              else
              {
                if($paquete==1)
                {
                    $monto=intval($eve['abono']);
                }
                else
                {
                    $monto=intval($eve['abono2']);
                }
              }
            }
            elseif($tipo_monto==2)
            {
              if(!Yii::$app->user->isGuest)
              {
                  $monto='';
              }
              else
              {
                if($paquete==1)
                {
                    $monto=intval($eve['monto_evento'])-intval($pago);
                }
                else
                {
                    $monto=intval($eve['monto_evento2'])-intval($pago);
                }

              }

            }
            elseif($tipo_monto==1)
            {
                if($paquete==1)
                {
                    $monto=$eve['monto_evento'];
                }
                else
                {
                    $monto=$eve['monto_evento2'];
                }

                //$monto='';
            }
            else{
                $monto=0;
            }
            //var_dump($programa['id_evento']);
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'monto'=>$monto,
            'abono'=>$eve['abono'],
            'pago'=>$pago,
            'paquete'=>$paquete,
            ];
      }
    }
    /**
     * Deletes an existing Cliente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cliente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Cliente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cliente::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findModelDireccion($id)
    {
        if (($modeldireccion = Direccion::findOne($id)) !== null) {
            return $modeldireccion;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionReporte()
    {
        if(!Yii::$app->user->identity)
        {
            return $this->redirect(['site/login']);
        }
        if(Yii::$app->user->identity->id_rol==2)
        {
            Yii::$app->session->setFlash('error', "No tiene permiso para acceder a ese modulo.");
            return $this->redirect(['site/index']);
        }
        $model = new ReporteForm();

        if ($model->load(Yii::$app->request->post()))
        {
            $searchModel = new ClienteSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->andWhere('cliente.fecha_ingreso like "'.$model->fecha1.'%"');
        }
        else
        {
            $searchModel = new ClienteSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        return $this->render('reporte', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionImportar()
    {
        $model= new ImportarForm();
         if ($model->load(Yii::$app->request->post()))
         {
            $uploadFile = UploadedFile::getInstance($model, 'archivo');
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($uploadFile->tempName);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            //echo count($sheetData);
            $i=0;
            for($i=2;$i<=(count($sheetData));$i++)
            {

            $modelcliente = new Cliente();
            $modeltipocliente = new TipoCliente();
            //var_dump($sheetData[$i]);
            echo $modelcliente->cedula=$sheetData[$i]['B'];
            $modelcliente->nombre=$sheetData[$i]['C'];
            $modelcliente->nombre_fantasia=$sheetData[$i]['D'];
            $modelcliente->sociedad=$sheetData[$i]['E'];
            $modelcliente->email1=$sheetData[$i]['F'];
            $modelcliente->email2=$sheetData[$i]['G'];
            $modelcliente->telefono=$sheetData[$i]['H'];
            $modelcliente->celular=$sheetData[$i]['I'];
            $modelcliente->lugar_residencia=$sheetData[$i]['J'];
            $modelcliente->clave_atv=$sheetData[$i]['K'];
            $modelcliente->comentario=$sheetData[$i]['L'];
            $tp=$modeltipocliente->find()->where(['tipo_cliente' => $sheetData[$i]['M']])->one();
            echo $modelcliente->idtipocliente=$tp->idtipocliente;
            var_dump($modelcliente->save(false));
            var_dump($modelcliente->getErrors());
            }

         }

        return $this->render('importar', [
            'model' => $model,
        ]);
    }

 public function actionCorreo()
    {
       $enlace = new Enlace();
        $post=0;
      if ($enlace->load(Yii::$app->request->post()))
         {



       $datos=Yii::$app->request->post();


          $cantidad1=0;
           $cantidad=0;
           $q=0;
           $w=0;
           $a=0;
           $i=0;
           $b=0;
           $c=0;
           $d=0;
           foreach($datos as $row);
           {
                $uso=$row['accion'];
                foreach ($row['valor'] as $p) {
                    $valor[$w]=$p;
                    $w++;
                }
                foreach ($row['id'] as $r) {
                    $id[$i]=$r;
                    $i++;
                }
                foreach ($row['link'] as $l) {
                    $link[$a]=$l;
                    $a++;
                }
                foreach ($row['nombre'] as $i) {
                    $nombre[$b]=$i;
                    $b++;
                }
                /*foreach ($row['apellido'] as $u) {
                    $apellido[$c]=$u;
                    $c++;
                }*/
                foreach ($row['correo'] as $t) {
                    $correo[$d]=$t;
                    $d++;
                }
           }

             // var_dump($uso);
             //   die();
                if($uso==1)
                {

                  for($q=0;$q<count($id);$q++)
                     {

                        if ($valor[$q] == 1)
                        {
                          $enlace = new Enlace();
                          $a=0;
                         // $prueba = new Enlace();
                         // $prueba = Enlace::find()->where(['id_cliente' => $id[$q]])->one();
                          $enlace = Enlace::find()->where(['id_cliente' => $id[$q]])->one();
                           if ($enlace != NULL)
                           {
                          echo $enlace->enlace=$link[$q];
                          $enlace->enviado=0;
                          $enlace->save();
                          $a=1;
                           }
                          else
                          {
                          $a=2;
                          $enlace = new Enlace();
                          echo $enlace->id_cliente=$id[$q];
                          echo $enlace->enlace=$link[$q];
                          $enlace->enviado=0;
                          $enlace->save();
                          }
                           $cantidad++;
                        }

                     }

                      Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                        [
                           'title' => 'Guardado de datos exitoso de: '.$cantidad.' Usuarios',
                            'text' => 'Su Informacion se Guardo con exito!!',
                            'confirmButtonText' => 'Aceptar!',
                            'timer' => 4000,
                        ]
                     ]);
                }
                else if ($uso == 2)
                  {

                    for($q=0;$q<count($id);$q++)
                     {

                        if ($valor[$q] == 1)
                        {
                          $enlace = new Enlace();
                          $a=0;
                         // $prueba = new Enlace();
                         // $prueba = Enlace::find()->where(['id_cliente' => $id[$q]])->one();
                          $enlace = Enlace::find()->where(['id_cliente' => $id[$q]])->one();
                           if ($enlace != NULL)
                           {
                          echo $enlace->enlace=$link[$q];
                          $enlace->enviado=1;
                          $enlace->save();
                          $a=1;
                           }
                          else
                          {
                          $a=2;
                          $enlace = new Enlace();
                          echo $enlace->id_cliente=$id[$q];
                          echo $enlace->enlace=$link[$q];
                          $enlace->enviado=1;
                          $enlace->save();
                          }
                        // var_dump($a);
                         // die();

                            $m=1;
                          if ($m==1) {
                                         // $content = "<p>Email: Abraham </p>";
                                          $content = "<p>Cordial saludo te da fotografía leal, " .$nombre[$q]."</p>";
                                          $content .= "En el siguiente link te enviamos las fotografías para descargar de tu ceremonia de grado ".$link[$q]."<br>Cordial saludo,</p>";
                                          Yii::$app->mailer->compose("@app/mail/layouts/html", ["content" => $content])
                                               ->setFrom('soporteventas@fotografialeal.com')
                                               ->setTo($correo[$q])
                                              ->setSubject('Correo FotoLeal')
                                               ->send();
                                              /////////////////////////////////////////Correo//////////////////////////////
                                        //  return true;
                              }
                               $cantidad1++;
                               if(($cantidad1 % 25)==0)
                                {
                                 //echo $i;
                                Yii::$app->mailer->getTransport()->stop();
                                sleep(10);
                                //Yii::$app->mailer->getTransport()->start();
                                //sleep(10);
                                }
                        }

                     }
                     Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, [
                        [
                           'title' => 'Envio de Correo Masivos de: '.$cantidad1.' Usuarios',
                            'text' => 'Su información se Envio con exito!',
                            'confirmButtonText' => 'Aceptar!',
                            'timer' => 4000,
                        ]
                     ]);

                  }


        return $this->redirect(['correo']);
        }

    $arreglo=Yii::$app->request->get();
    $evento=Yii::$app->request->get('Evento');
    $id_programa=Yii::$app->request->get('Cliente');
       //var_dump($id_programa['id_programa']);
       //exit();

        $model = new Cliente();


        $cliente = new Cliente();
        $pago = new Pago();
      /* if(Yii::$app->request->get())
        {
            $model1 = $cliente->find()->where(['id_programa' => $id_programa['id_programa']])->all();
        }
        else{$model1 = $cliente->find()->all();}
        //$model1 = $cliente->find()->where(['id_programa' =>1])->all();
      */
        $form = new FormSearch;
        $search = null;

         //$model1 = $cliente->find();
         $model1 = Cliente::find();
              /*  $model1->innerJoin("pago", "cliente.id_cliente=pago.id_cliente");
                $model1->with("pagos");
                $model1->andFilterWhere(['observaciones'=>1]);*/

                /*"sELECT * FROM pago
                          INNER JOIN cliente on pago.id_cliente= cliente.id_cliente
                          INNER JOIN enlace on enlace.id_cliente = cliente.id_cliente
                          WHERE cliente.id_cliente=pago.id_cliente and pago.observaciones='1'"

*/

                $query1 = "sELECT * FROM pago
                          INNER JOIN cliente on pago.id_cliente= cliente.id_cliente
                          WHERE pago.confirmacion=1 and (pago.observaciones='1' or pago.observaciones='2')";
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand($query1);

                $model1 = $command->queryAll();
                //var_dump($model1);
                //die();
                if(Yii::$app->request->get())
                {


                    if($id_programa['id_programa']!=0)
                    {
                      $query2 = "select *
                                 from cliente
                                where id_cliente not in (select id_cliente from pago) and cliente.id_programa =".$id_programa['id_programa'];
                      $query3 = "sELECT * FROM cliente
                                  INNER JOIN pago ON cliente.id_cliente = pago.id_cliente
                                  WHERE pago.confirmacion=1 and pago.observaciones = 0 AND pago.id_cliente not in (SELECT  pago.id_cliente FROM pago WHERE observaciones = 2)  and cliente.id_programa =".$id_programa['id_programa'].
                                  " order by cliente.cedula";

                      $query1 = "sELECT * FROM pago
                                  INNER JOIN cliente on pago.id_cliente= cliente.id_cliente
                                  WHERE (pago.confirmacion=1 and pago.observaciones='1' and cliente.id_programa ='".$id_programa['id_programa']."')
                                  or (pago.confirmacion=1 and pago.observaciones='2' and cliente.id_programa ='".$id_programa['id_programa']."')";
                                  //echo $query1;
                    }
                    else {
                      $query2 = "select *
                                 from cliente
                                where id_cliente not in (select id_cliente from pago)";
                      $query3 = "sELECT * FROM cliente
                                INNER JOIN pago ON cliente.id_cliente = pago.id_cliente
                                WHERE pago.confirmacion=1 and pago.observaciones = 0 AND pago.id_cliente not in (SELECT  pago.id_cliente FROM pago WHERE observaciones = 2)
                                order by cliente.cedula ";
                      $query1 = "sELECT * FROM pago
                                  INNER JOIN cliente on pago.id_cliente= cliente.id_cliente
                                  WHERE pago.confirmacion=1 and (pago.observaciones='1' or pago.observaciones='2')";
                    }

                      $connection = Yii::$app->getDb();
                      $command = $connection->createCommand($query1);
                      $model1 = $command->queryAll();

                      $connection = Yii::$app->getDb();
                      $command = $connection->createCommand($query2);
                      $model3 = $command->queryAll();


                      $connection = Yii::$app->getDb();
                      $command = $connection->createCommand($query3);
                      $model4 = $command->queryAll();
                }
                else{
                  $model3=$model4='';
                }
                //var_dump($query1);
                //var_dump($model1->all());
                //die();
         $model2 = $pago->find()->all();

        if($model->load(Yii::$app->request->get()))
        {
            if ($form->validate())
            {
                $search = Html::encode($model->id_programa);
                  $ev=Yii::$app->request->get();
                  $id_evento=$ev['Evento']['id_evento'];
            //$query = "SELECT * FROM cliente a, pago b WHERE a.id_programa = '$search' and a.id_cliente=b.id_cliente and b.observaciones='1'";

                $query = "SELECT * FROM cliente a INNER JOIN pago b WHERE a.id_programa = '$search' and a.id_cliente=b.id_cliente and b.observaciones='1' and b.confirmacion=1";
                $query11 = "sELECT * FROM pago
                            INNER JOIN cliente on pago.id_cliente= cliente.id_cliente
                            INNER JOIN programa on programa.id_programa=cliente.id_programa
                            INNER JOIN evento on evento.id_evento = programa.id_evento";
                if($id_evento)
                {
                    $query1= $query11. " WHERE (pago.confirmacion=1 and pago.observaciones='1' and evento.id_evento='".$id_evento."')
                    OR (pago.confirmacion=1 and pago.observaciones='2' and evento.id_evento='".$id_evento."')";
                    if($model->id_programa!=0)
                    {
                      $query1=$query11. " WHERE (pago.confirmacion=1 and pago.observaciones='1' and evento.id_evento='".$id_evento."' and programa.id_programa='".$model->id_programa."')
                      OR (pago.confirmacion=1 and pago.observaciones='2' and evento.id_evento='".$id_evento."' and programa.id_programa='".$model->id_programa."')";
                      //var_dump($query1);
                      //exit();
                    }
                }
                          //INNER JOIN enlace on enlace.id_cliente = cliente.id_cliente

                $connection = Yii::$app->getDb();
                $command = $connection->createCommand($query1);

                $model1 = $command->queryAll();
                //$query2 = Cliente::find();
                //$query2->innerJoin("pago", "cliente.id_cliente=pago.id_cliente");
                //$query2->with("pagos");
                //$query2->andFilterWhere(['observaciones'=>1]);
                //$query2->andFilterWhere(['id_programa'=>$search]);
                //$model1=$query2;

                 //$model1 = $model->findBySql($query2)->all();
                  //$model2 = $model->findBySql($query2)->all();
                 // var_dump($query2);
                 // die();
               //return $this->render("correo", ["model" => $model,"model1" => $query2,"form" => $form, "search" => $search,"enlace" => $enlace,"model2" => $model2 ]);
                   //var_dump($query);
                  //die();
            }
            else
            {
                $form->getErrors();
            }
        }
        //var_dump($query1);
        //echo '<pre>' , var_dump($model1) , '</pre>';
        return $this->render("correo", [
          "model" => $model,
          "model1" => $model1,
          "model3" => $model3,
          "model4" => $model4,
          "form" => $form,
          "search" => $search,
          "enlace" => $enlace,
          "model2" => $model2 ]);

    }

    public function actionNotificacion()
    {
        $model = new Cliente();

         $correo=Yii::$app->request->post();
         /*var_dump($correo);
         exit();*/
         $evento=$correo['Cliente']['evento'];
         $id_programa=$correo['Cliente']['programa'];
         $email=$correo['Cliente']['correo'];
         $sql="sELECT * FROM cliente INNER join pago on pago.id_cliente = cliente.id_cliente
        where pago.confirmacion=1 and pago.observaciones='1' and cliente.id_programa='".$id_programa."'";
         $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $cliente = $command->queryAll();
        $tabla="<table>";
        foreach($cliente as $c)
        {
            $tabla.="<tr><td>nombre:".$c['nombre'].' '.$c['apellido']."</td>
                        <td>cedula:".$c['cedula']."</td>
                        <td>movil:".$c['celular']."</td>
                        <td>direccion:".$c['id_direccion']."</td></tr>";
        }
        $tabla.="</table>";
        //exit();
         //$cliente= $model->find()->where(['id_programa' =>$id_programa ])->all();
         //exit();
        //$correo = Yii::$app->request->get('correo');
        //$correo = 'laumanie@gmail.com';
        $content = "<p>Saludos, envio listado de clientes para hacer la entrega</p><br/>".$tabla ;
        //$content .= "Se hace el envio de el link donde esta para descargar las imagenes " .$link[$q]. "</p>";
        Yii::$app->mailer->compose("@app/mail/layouts/html", ["content" => $content])
        ->setFrom('soporteventas@fotografialeal.com')
        ->setTo($email)
        ->setSubject('Listado para entregas')
        ->attach(Yii::$app->request->hostInfo.'/fotoleal/runtime/export/Reporte_de_envio.xls')
        ->send();
         Yii::$app->session->setFlash(\dominus77\sweetalert2\Alert::TYPE_SUCCESS, 'Correo enviado al proveedor!');


        return $this->redirect(['envio']);
        //return $this->render('notificacion');
    }

    public function actionEnvio()
    {
    /*$query2 = "select *
                           from cliente
                          where id_cliente not in (select id_cliente from pago)";
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand($query2);
*/
  //              $model3 = $command->queryAll();
    $searchModel = new ClienteSearch();

    $post = Yii::$app->request->get();
    $evento='';
    $id_programa='';
    if($post!=null)
    {
      if(!isset($post['Evento']['id_evento']))
      {
        $evento=null;
      }
      else {
        $evento=$post['Evento']['id_evento'];
      }
      if(!isset($post['Cliente']['id_programa']))
      {
        $id_programa=null;
      }
      else {
      $id_programa=$post['Cliente']['id_programa'];
      }

        $dataProvider = $searchModel->search($post);
    }

    //echo "<br><br><br><br>";var_dump($post);
    $dataProvider = $searchModel->search($post);

      $model = new Cliente();

        return $this->render("envio", [
            "model" => $model,
    //        "model3" => $model3,
            "searchModel"=>$searchModel,
            "dataProvider" => $dataProvider,
            "evento" => $evento,
            "id_programa"=>$id_programa
            ]);

    }

    public function actionListadopago()
    {
      if (isset($_GET['id']))
      {
        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("Aquí va el creador, como cadena")
            ->setLastModifiedBy('Parzibyte') // última vez modificado por
            ->setTitle('Mi primer documento creado con PhpSpreadSheet')
            ->setSubject('El asunto')
            ->setDescription('Este documento fue generado para parzibyte.me')
            ->setKeywords('etiquetas o palabras clave separadas por espacios')
            ->setCategory('La categoría');
        $hoja = $documento->getActiveSheet();
        $hoja->setTitle("El título de la hoja");
        $hoja->setCellValueByColumnAndRow(1, 1, "Un valor en 1, 1");
        $hoja->setCellValue("B2", "Este va en B2");
        $hoja->setCellValue("A3", "Parzibyte");
        $writer = new Xlsx($documento);
        $writer->save('archivos/ejemplo3.xlsx');
        }
    $searchModel = new ClienteSearch();

    $post = Yii::$app->request->get();
    $evento='';
    $programa='';
    $p1=[[
      'id' => 1,
      'cedula' => '',
      'facultad'=>'',
      'programa'=>'',
      'nombre'=>'',
      'bloque'=>'',
      'fila'=>'',
      'silla'=>'',
      'id_programa'=>'',
      ]];
  /*  if($post!=null)
    {
        $evento=$post['Evento']['id_evento'];
        $id_programa=$post['Cliente']['id_programa'];
        $dataProvider = $searchModel->search4($post);
    }*/

    //echo "<br><br><br><br>";var_dump($post);
    $dataProvider = $searchModel->search4($post);

      $model = new Cliente();
//////////////////////////////////////////////////////////////////
if ($model->load(Yii::$app->request->post()))
  {
     $programa=$model->id_programa;
     $uploadFile = UploadedFile::getInstance($model, 'carga');
     //var_dump($programa);
     //exit();
     if($uploadFile==null || $programa=='0')
     {
       $provider = new ArrayDataProvider([
           'allModels' => $p1,
           'pagination' => [
               'pageSize' => 30000,
           ],
           'sort' => [
               'attributes' => ['id', 'name'],
           ],
       ]);
       return $this->render("listadopago", [
           "model" => $model,
   //        "model3" => $model3,
           "searchModel"=>$searchModel,
           "dataProvider" => $dataProvider,
           "provider" => $provider,
           "programa1" =>$programa,
           ]);
     }
     $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
     $reader->setReadDataOnly(true);
     $spreadsheet = $reader->load($uploadFile->tempName);
     $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
     $documento = new Spreadsheet();
         $hoja = $documento->getActiveSheet();
         $hoja->setTitle("El título de la hoja");
         $writer = new Xls($documento);

//$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
//$reader->setReadDataOnly(true);
//$spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT'].'/fotoleal/web/archivos/formato.xlsx');
//$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
//var_dump($programa);

//exit();
$p[]='';
$i=0;
$j=0;
$k=1;
foreach ($sheetData as $k => $value) {
  $p[$i]=[
    'facultad'=>$value['A'],
    'programa'=>$value['B'],
    'nombre'=>$value['C'],
    'bloque'=>$value['D'],
    'fila'=>$value['E'],
    'silla'=>$value['F'],
    'cedula'=>$value['G'],
    'id_programa'=>$programa,
];
$hoja->setCellValue("A".$k, $value['A']);
$hoja->setCellValue("B".$k, $value['B']);
$hoja->setCellValue("C".$k, $value['C']);
$hoja->setCellValue("D".$k, $value['D']);
$hoja->setCellValue("E".$k, $value['E']);
$hoja->setCellValue("F".$k, $value['F']);
$hoja->setCellValue("G".$k, $value['G']);
//$hoja->setCellValue("H".$k, $programa);
$pp='pago';

$pago = Pago::find()
->innerJoin("cliente", "cliente.id_cliente=pago.id_cliente")
->innerJoin("direccion", "cliente.id_direccion=direccion.id_direccion")
->innerjoin("programa","programa.id_programa=cliente.id_programa")
->innerjoin("evento","evento.id_evento=programa.id_evento")
->where(["cliente.cedula"=>$value['G'],"programa.id_programa"=>$programa])
->all();
foreach ($pago as $kk ) {
  $pp.=$kk['observaciones'];
}
if($k==1)
{
  $hoja->setCellValue("H1","Status");
}
else {
  if($pp=='pago')
  {
    $hoja->setCellValue("H".$k, "sin pago");
  }
  elseif($pp=='pago1'){
    $hoja->setCellValue("H".$k, "pago total");
  }
  elseif($pp=='pago02'){
    $hoja->setCellValue("H".$k, "pago total");
  }
  elseif($pp=='pago0'){
    $hoja->setCellValue("H".$k, "abono");
  }

}

if($i==0)
{

}
else {
  $p1[$j-1]=$p[$i];
}

  $i++;
  $j++;
  $k++;
  }
  //var_dump($p);

$data = [
    ['id' => 1, 'name' => 'name 1'],
    ['id' => 2, 'name' => 'name 2'],
    ['id' => 100, 'name' => 'name 100'],
];
//var_dump($data);
//exit();
$writer->save('archivos/reportepago.xls');
}
$provider = new ArrayDataProvider([
    'allModels' => $p1,
    'pagination' => [
        'pageSize' => 30000,
    ],
    'sort' => [
        'attributes' => ['id', 'name'],
    ],
]);

/////////////////////////////////////////////////
        return $this->render("listadopago", [
            "model" => $model,
    //        "model3" => $model3,
            "searchModel"=>$searchModel,
            "dataProvider" => $dataProvider,
            "provider" => $provider,
            "programa1" =>$programa,
            ]);

    }



}
