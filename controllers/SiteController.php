<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\BuscarForm;
use app\models\ContactForm;
use app\models\Cliente;
use app\models\ClienteSearch;
use app\models\Usuario;
use app\models\Evento;
use app\models\UsuarioSearch;
use app\models\Pago;
use app\models\PagoFoto;
use app\models\PagoSearch;
use app\models\Direccion;
use app\models\FotoAdicional;
use QRcode;

//ini_set("memory_limit","4M");
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionQrr()
    {
       return $this->render('qr');
    }
    public function actionIndex()
    {
        /*$model = new Cliente();
        $modelevento= new Evento();
        $modeldireccion=new Direccion();
        return $this->render('/cliente/update1', [
            'model' => $model,
            'modeldireccion' => $modeldireccion,
        ]);*/
        return $this->redirect('index.php/cliente/update1');
    }
    public function actionBuscar()
    {
        $model = new BuscarForm();

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
        //var_dump($searchModel);


        return $this->render('buscar',[
            'model'=>$model,
        ]);
    }

    public function actionAdmin()
    {
        $model = new BuscarForm();
        $vendedor = new Usuario();
        $vende=$vendedor->find()->where(['id_rol' => 2])->all();
        $eve = new Evento();
        $fe3antes= explode('-', date('Y-m-d'));
        $mes3antes=intval($fe3antes[1])-3;
        $mes2despues=intval($fe3antes[1])+2;
        if($mes3antes<1)
        {$mes3antes=01;}
        if($mes2despues>12)
        {$mes2despues=12;}
        $model->fecha1=$fe3antes[0].'-'.$mes3antes.'-'.$fe3antes[2];
        $model->fecha2=$fe3antes[0].'-'.$mes2despues.'-'.$fe3antes[2];
        $searchModel = new ClienteSearch();
        //echo $model->fecha1;
        //echo $model->fecha2;
        $dataProvider = $searchModel->search3(Yii::$app->request->queryParams,$model->fecha1,$model->fecha2);
        if($model->load(Yii::$app->request->post()))
        {
          $searchModel = new ClienteSearch();
          $dataProvider = $searchModel->search3(Yii::$app->request->queryParams,$model->fecha1,$model->fecha2);
            //$evento=$eve->find()->where(['between', 'fecha_evento', $model->fecha1, $model->fecha2 ])->all();
            $connection = Yii::$app->getDb();
            $sql="select distinct evento.nombre_evento,evento.id_evento from cliente
inner join programa on cliente.id_programa= programa.id_programa
inner join evento on programa.id_evento = evento.id_evento
where evento.fecha_evento between '".$model->fecha1."' and '".$model->fecha2."'";
            $command = $connection->createCommand($sql);

            $evento = $command->queryAll();
            //var_dump($evento);

        }
        else
        {

            $evento=$eve->find()->all();
        }
        //var_dump($evento);
       // echo $sql;
        //exit();
        $sqlepay="sELECT sum(pago.monto) as suma FROM pago
				          INNER JOIN cliente on pago.id_cliente = cliente.id_cliente
				          INNER JOIN programa on programa.id_programa = cliente.id_programa
                  INNER JOIN evento on evento.id_evento = programa.id_evento
                  WHERE pago.tipo_pago='electronico' and pago.confirmacion=1 and evento.fecha_evento BETWEEN '".$model->fecha1."' and '".$model->fecha2."'";

        $sqlefectivo="sELECT sum(pago.monto) as suma FROM pago
				                  INNER JOIN cliente on pago.id_cliente = cliente.id_cliente
				                  INNER JOIN programa on programa.id_programa = cliente.id_programa
                          INNER JOIN evento on evento.id_evento = programa.id_evento
                          WHERE pago.tipo_pago='efectivo' and pago.confirmacion=1 and evento.fecha_evento BETWEEN '".$model->fecha1."' and '".$model->fecha2."'";

        $sqlcliente="sELECT * FROM cliente
                      INNER JOIN programa ON cliente.id_programa=programa.id_programa
                      INNER JOIN evento on programa.id_evento=evento.id_evento WHERE evento.fecha_evento BETWEEN '".$model->fecha1."' and '".$model->fecha2."'";

        $sqlpagocliente="sELECT * FROM `pago`
                        INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                        INNER JOIN programa ON programa.id_programa=cliente.id_programa
                        INNER JOIN evento ON evento.id_evento=programa.id_evento
                        WHERE pago.confirmacion=1 and evento.fecha_evento BETWEEN '".$model->fecha1."' and '".$model->fecha2."'";

        $sqlclienteenlace="sELECT * FROM `enlace`
                          INNER JOIN cliente ON cliente.id_cliente=enlace.id_cliente
                          INNER JOIN programa on programa.id_programa=cliente.id_programa
                          INNER JOIN evento ON evento.id_evento=programa.id_evento
                          WHERE evento.fecha_evento BETWEEN '".$model->fecha1."' and '".$model->fecha2."'";

        $sqlclientemensual="sELECT * FROM `cliente`
                          INNER JOIN programa ON programa.id_programa=cliente.id_programa
                          INNER JOIN evento ON evento.id_evento=programa.id_evento
                          WHERE month(evento.fecha_evento)";
                          //AND pago.observaciones = 0 AND pago.id_cliente not in
              //(SELECT  pago.id_cliente FROM pago WHERE observaciones = 2)";
        $sqlclientemensualabono="sELECT * FROM `cliente`
                          INNER JOIN programa ON programa.id_programa=cliente.id_programa
                          INNER JOIN evento ON evento.id_evento=programa.id_evento
                          INNER JOIN pago ON pago.id_cliente = cliente.id_cliente
                          WHERE pago.observaciones = 0 and pago.confirmacion=1 AND pago.id_cliente not in
                          (SELECT  pago.id_cliente FROM pago WHERE observaciones = 2)
                          and pago.paquete=1
                          and month(evento.fecha_evento) ";

        $sqlclientemensualabono2="sELECT * FROM `cliente`
                          INNER JOIN programa ON programa.id_programa=cliente.id_programa
                          INNER JOIN evento ON evento.id_evento=programa.id_evento
                          INNER JOIN pago ON pago.id_cliente = cliente.id_cliente
                          WHERE pago.observaciones = 0 and pago.confirmacion=1 AND pago.id_cliente not in
                          (SELECT  pago.id_cliente FROM pago WHERE observaciones = 2)
                          and pago.paquete=0
                          and month(evento.fecha_evento) ";


        $sqlpagomensual="sELECT * FROM `pago`
                          INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                          INNER JOIN programa ON programa.id_programa=cliente.id_programa
                          INNER JOIN evento ON evento.id_evento=programa.id_evento
                          WHERE pago.confirmacion=1 AND ((pago.observaciones = 0 and pago.confirmacion=1 AND pago.id_cliente not in
                                (SELECT  pago.id_cliente FROM pago WHERE observaciones = 2))
                          OR (pago.observaciones = 2)
                          OR (pago.observaciones = 1))
                          and month(evento.fecha_evento)";

        $sqlpagoeventomensual="sELECT SUM(monto) as suma FROM `pago`
                                INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                                INNER JOIN evento ON evento.id_evento=programa.id_evento
                                WHERE pago.confirmacion=1 and month(evento.fecha_evento)";

        $sqlpagoeventomensualabono="sELECT SUM(monto) as suma FROM `pago`
                                INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                                INNER JOIN evento ON evento.id_evento=programa.id_evento
                                WHERE pago.observaciones = 0 and pago.confirmacion=1 AND pago.id_cliente not in
                                (SELECT  pago.id_cliente FROM pago WHERE observaciones = 2)
                                and month(evento.fecha_evento)";

$sqlpagoeventomensualtotalrecaudado="sELECT SUM(monto) as suma FROM `pago`
                                    INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                                    INNER JOIN programa ON programa.id_programa=cliente.id_programa
                                    INNER JOIN evento ON evento.id_evento=programa.id_evento
                                    WHERE pago.confirmacion=1 and month(evento.fecha_evento)";

        $sqleventomensual="sELECT * FROM `evento` WHERE month(evento.fecha_evento)";


        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sqlepay);
        $epay1 = $command->queryAll();

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sqlefectivo);
        $efectivo1 = $command->queryAll();

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sqlcliente);
        $cliente1 = $command->queryAll();


        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sqlpagocliente);
        $pagocliente = $command->queryAll();

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sqlclienteenlace);
        $clienteenlace = $command->queryAll();


        if(!$model->fecha1 || !$model->fecha2)
        {
          $fecha_inicial=$fecha_final=01;
        }
        else {
          $mescliente1=explode("-",$model->fecha1);
          $fecha_inicial=(int)$mescliente1[1];
          $mescliente2=explode("-",$model->fecha2);
          $fecha_final=(int)$mescliente2[1];
        }

        $j=$fecha_inicial;
        for ($i=0;$i<=$fecha_final-$fecha_inicial;$i++)
        {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sqlclientemensual."=".$j);
            $clientemensual[$i] = count($command->queryAll());
            $j++;
          //echo $sqlcm[$i]=$sqlclientemensual."=".$i;
        }

        $ja=$fecha_inicial;
        for ($i=0;$i<=$fecha_final-$fecha_inicial;$i++)
        {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sqlclientemensualabono."=".$ja);
            $clientemensualabono[$i] = count($command->queryAll());
            $ja++;
          //echo $sqlcm[$i]=$sqlclientemensualabono."=".$ja;
        }
        $ja2=$fecha_inicial;
        for ($i=0;$i<=$fecha_final-$fecha_inicial;$i++)
        {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sqlclientemensualabono2."=".$ja2);
            $clientemensualabono2[$i] = count($command->queryAll());
            $ja2++;
          //echo $sqlcm[$i]=$sqlclientemensualabono2."=".$ja2;
        }

        $k=$fecha_inicial;
        for ($i=0;$i<=$fecha_final-$fecha_inicial;$i++)
        {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sqlpagomensual."=".$k);
            $pagomensual[$i] = count($command->queryAll());
            $k++;
          //echo $sqlcm[$i]=$sqlclientemensual."=".$i;
        }

        $h=$fecha_inicial;
        for ($i=0;$i<=$fecha_final-$fecha_inicial;$i++)
        {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sqleventomensual."=".$h);
            $todo = $command->queryAll();
            $eventomensual[$i] =count($todo);
            if(count($todo))
            {
              foreach ($todo as $k) {

              $eventomensualmonto[$i] = $k['monto_evento'];
              $eventomensualmonto2[$i] = $k['monto_evento2'];
              }
            }
            else {
              $eventomensualmonto[$i] =0;
              $eventomensualmonto2[$i] =0;
            }

          //echo $sqlcm[$i]=$sqleventomensual."=".$h;
            $h++;
        }

        $z=$fecha_inicial;
        for ($i=0;$i<=$fecha_final-$fecha_inicial;$i++)
        {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sqlpagoeventomensual."=".$z);
            $pagoeventomensual[$i] =$command->queryOne();

        //  echo $sqlcm[$i]=$sqlpagoeventomensual."=".$z;
            $z++;
        }

        $za=$fecha_inicial;
        for ($i=0;$i<=$fecha_final-$fecha_inicial;$i++)
        {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sqlpagoeventomensualabono."=".$za);
            $pagoeventomensualabono[$i] =$command->queryOne();

          //echo $sqlcm[$i]=$sqlpagoeventomensualabono."=".$za;
            $za++;
        }

        $zb=$fecha_inicial;
        for ($i=0;$i<=$fecha_final-$fecha_inicial;$i++)
        {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($sqlpagoeventomensualtotalrecaudado."=".$zb);
            $pagoeventomensualtotalrecaudado[$i] =$command->queryOne();

          //echo $sqlcm[$i]=$sqlpagoeventomensualtotalrecaudado."=".$zb;
            $zb++;
        }
        for ($i=0;$i<=$fecha_final-$fecha_inicial;$i++)
        {
          $montototalrecaudadomensual[$i]=intval($pagoeventomensualtotalrecaudado[$i]['suma']);
          //var_dump($pagoeventomensualabono);
        }
      //  echo $z;
        //exit();
        for($i=0;$i<count($pagoeventomensual);$i++)
        {
          $pagoeventomensual1[$i]=intval($pagoeventomensual[$i]['suma']);
        }

        for($i=0;$i<count($pagoeventomensualabono);$i++)
        {
          $pagoeventomensual1abono[$i]=intval($pagoeventomensualabono[$i]['suma']);
        }



      //var_dump(json_encode($pagoeventomensual1));
      //exit();

      $eventoprograma=$eve->find()->all();
        //var_dump($clientemensual);
        $pago= new Pago();
        $total= $pago->find()->sum('monto');

        $epay =$pago->find()->where(['like','tipo_pago','epay'])->sum('monto');
        $efectivo= $pago->find()->where(['like','tipo_pago','efectivo'])->sum('monto');
        $total= $pago->find()->sum('monto');
        $i=0;
        $vend[]='';
        $pag[]='';
        $color[]='';
        $borde[]='';
        //var_dump($vende);
        foreach ($vende as $v){
        	$vend[$i]=$v['nombre'].' '.$v['apellido'];
          $foto[$i]=$v['foto'];
          $id_vend[$i]=$v['id_usuario'];
        	$pag[$i]=$pago->find()->where(['id_usuario'=>$v['id_usuario'],'confirmacion'=>1])->sum('monto');
        	$color[$i]="rgba(".mt_rand(0,255).','.mt_rand(0,255).','.mt_rand(0,255).',0.5)';
        	$borde[$i]="rgba(".mt_rand(0,255).','.mt_rand(0,255).','.mt_rand(0,255).',1)';
        	$i++;
        }
        $i=0;
        $eventos[]='';
        $evento_monto[]='';
        $total_evento[]='';

        foreach ($evento as $ev) {
            $eventos[$i]=$ev['nombre_evento'];
            $connection = Yii::$app->getDb();
        	$sql="sELECT SUM(monto) as suma FROM pago
				INNER JOIN cliente on pago.id_cliente = cliente.id_cliente
				INNER JOIN programa on programa.id_programa = cliente.id_programa
				WHERE pago.confirmacion=1 and programa.id_evento = ".$ev['id_evento'];
            $command = $connection->createCommand($sql);
            $evento_monto[$i] = $command->queryAll();
            $i++;
        }
        //var_dump($evento_monto);exit();
        if($evento_monto[0]!="")
        {
	        for($j=0;$j<count($evento_monto);$j++)
	        {
	         $total_evento[$j]=$evento_monto[$j][0]['suma'];
	        }

        }

$meses=[1=>"Enero", 2=>"Febrero", 3=>"Marzo", 4=>"Abril", 5=>"Mayo", 6=>"Junio", 7=>"Julio", 8=>"Agosto", 9=>"Septiembre", 10=>"Octubre", 11=>"Noviembre", 12=>"Diciembre"];
$me[]='';
        $fechainicio=$model->fecha1;
        $fechafin=$model->fecha2;

        if(!$fechainicio || !$fechafin)
        {

        }
        else {
          $fechai= explode("-",$fechainicio);
          $fechaf= explode("-",$fechafin);
          $i=0;
          $fi= intval ($fechai[1]);
          $ff= intval ($fechaf[1]);
          while ( $fi<=$ff) {
              if($fi==12)
              {
                  //$fi=1;
              }
                  $me[$i]=$meses[$fi];

              $sqlpagocliente2="sELECT SUM(monto) as suma FROM pago
                  INNER JOIN cliente on pago.id_cliente = cliente.id_cliente
                  INNER JOIN programa on programa.id_programa = cliente.id_programa
                  INNER JOIN evento ON evento.id_evento=programa.id_evento
                  where pago.confirmacion=1 and evento.fecha_evento
                  BETWEEN '".$fechai[2]."-".$fi."-01' and '".$fechaf[2]."-".$fi."-31'";
                  $connection = Yii::$app->getDb();
          $command = $connection->createCommand($sqlpagocliente2);
          $pagocliente2 = $command->queryAll();

          //var_dump($pagocliente2);
              $fi++;
              $i++;
          }
        }
        for($i=0;$i<count($id_vend);$i++)
        {
          $sqlvendedormonto="sELECT usuario.nombre,usuario.apellido,usuario.id_usuario,sum(monto) as mon FROM usuario
                            INNER JOIN pago ON pago.id_usuario=usuario.id_usuario
                            INNER JOIN cliente on cliente.id_cliente=pago.id_cliente
                            INNER JOIN programa ON programa.id_programa=cliente.id_programa
                            INNER JOIN evento ON evento.id_evento=programa.id_evento
                            WHERE usuario.id_usuario='".$id_vend[$i]."' and pago.confirmacion=1
                            and evento.fecha_evento BETWEEN '".$model->fecha1."' and '".$model->fecha2."'";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sqlvendedormonto);
        $vendedormonto[$i] = $command->queryAll();
        foreach ($vendedormonto[$i] as $k) {
          $vendedormonto2[]=$k['mon'];
          $vendedormonto2nombre[]=$k['nombre']." ".$k['apellido'];
        }
        }
$sql1="sELECT * FROM pago
                INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                INNER JOIN evento ON evento.id_evento=programa.id_evento
                WHERE evento.fecha_evento BETWEEN '".$model->fecha1."' and '".$model->fecha2."'
                and pago.confirmacion=1
                AND (pago.observaciones='1' or pago.observaciones='2')";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql1);
        $detallepago1 = $command->queryAll();

        $sql2="sELECT * FROM pago
                INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                INNER JOIN evento ON evento.id_evento=programa.id_evento
                WHERE pago.confirmacion=1 and evento.fecha_evento BETWEEN '".$model->fecha1."' and '".$model->fecha2."'
                AND pago.observaciones = 0 AND pago.id_cliente not in
              (SELECT  pago.id_cliente FROM pago WHERE observaciones = 2)";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql2);
        $detallepago2 = $command->queryAll();

$pagostotalesrealizados=count($detallepago1)+count($detallepago2);
        return $this->render('admin',[
        	'vend'=>$vend,
          'foto'=>$foto,
          'vendedormonto'=>$vendedormonto,
          'vendedormonto2'=>$vendedormonto2,
          'vendedormonto2nombre'=>$vendedormonto2nombre,
          'id_vend'=>$id_vend,
            'meses'=>$me,
        	'pag' =>$pag,
        	'total'=>$total,
        	'epay' => $epay1[0]['suma'],
        	'efectivo' => $efectivo1[0]['suma'],
        	'color' => $color,
        	'borde' => $borde,
            'model'=>$model,
            'eventos' => $eventos,
            'evento' => $evento,
            'cliente1' => $cliente1,
            'pagocliente' => $pagocliente,
            'evento_monto' => $total_evento,
            'clienteenlace' => $clienteenlace,
            'fecha1' =>$model->fecha1,
            'fecha2' =>$model->fecha2,
            'clientemensual' => $clientemensual,
            'clientemensualabono' => $clientemensualabono,
            'clientemensualabono2' => $clientemensualabono2,
            'pagomensual'=> $pagomensual,
            'pagoeventomensual' => $pagoeventomensual1,
            'pagoeventomensualabono' => $pagoeventomensual1abono,
            'eventomensual' => $eventomensual,
            'eventoprograma' => $eventoprograma,
            'sqlcliente' => $sqlcliente,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagostotalesrealizados' => $pagostotalesrealizados,
            'eventomensualmonto' => $eventomensualmonto,
            'eventomensualmonto2' => $eventomensualmonto2,
            'pagoeventomensualtotalrecaudado' => $pagoeventomensualtotalrecaudado,
            'montototalrecaudadomensual' => $montototalrecaudadomensual,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
       /* if (!Yii::$app->user->isGuest) {
            //return $this->goHome();
        }*/

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (!Yii::$app->user->isGuest) {
                # code...
            $searchModel = new ClienteSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            /*return $this->render('/cliente/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            ]);*/
            //$this->setState('rol', $record->name);
            $path=Yii::getAlias('@webroot').'/index.php';
            if (Yii::$app->user->identity->id_rol==2) {
              return $this->redirect('../cliente/update1');
            }
            else {
              return $this->redirect('../site/admin');
            }

            }
        }
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionDona()
    {
      if (Yii::$app->request->isAjax) {
      $evento = Yii::$app->request->post();
      $eve=$evento['evento'];
      $sql = "sELECT COUNT(usuario.id_usuario) as ncliente, usuario.id_usuario , usuario.usuario from usuario
              INNER JOIN pago ON pago.id_usuario = usuario.id_usuario
              INNER JOIN cliente ON cliente.id_cliente = pago.id_cliente
              INNER JOIN programa on programa.id_programa = cliente.id_programa
              INNER JOIN evento ON evento.id_evento = programa.id_evento
              WHERE pago.confirmacion=1 and ((pago.observaciones = 0 AND pago.id_cliente not in
                                (SELECT  pago.id_cliente FROM pago WHERE observaciones = 2))
                          OR (pago.observaciones = 2)
                          OR (pago.observaciones = 1))
                          AND evento.id_evento = '".$eve."'
              GROUP BY id_usuario";
              $connection = Yii::$app->getDb();
              $command = $connection->createCommand($sql);
              $vendedorcliente = $command->queryAll();
              $i=0;
              if(count($vendedorcliente)>0)
              {
              foreach ($vendedorcliente as $k) {
                $vendedor[$i]=$k['usuario'];
                $cantidad_cliente[$i]=$k['ncliente'];
                $i++;
              }
              $nc=count($vendedorcliente);
            }
            else {
              $vendedor='';
              $cantidad_cliente='';
              $nc='';
            }
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      return [
      'vendedor' => $vendedor,
      'cantidad_cliente' => $cantidad_cliente,
      'nc' => $nc,

          ];
      }
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
/*SELECT * FROM evento
INNER JOIN programa ON programa.id_evento = evento.id_evento
INNER JOIN cliente ON cliente.id_programa = programa.id_programa
WHERE evento.fecha_evento BETWEEN '2019-01-01'and '2019-06-30'*/
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionDetalleevento($fecha1,$fecha2)
    {
        $connection = Yii::$app->getDb();
        $sql1="sELECT DISTINCT(evento.nombre_evento) FROM evento
            INNER JOIN programa ON programa.id_evento = evento.id_evento
            INNER JOIN cliente ON cliente.id_programa = programa.id_programa
            WHERE evento.fecha_evento BETWEEN '".$fecha1."'and '".$fecha2."' AND
            cliente.asistencia=1";
        $command = $connection->createCommand($sql1);
        $Detalleevento = $command->queryAll();
        $sql2="sELECT DISTINCT(evento.nombre_evento) FROM evento
            INNER JOIN programa ON programa.id_evento = evento.id_evento
            INNER JOIN cliente ON cliente.id_programa = programa.id_programa
            WHERE evento.fecha_evento BETWEEN '".$fecha1."'and '".$fecha2."'";
        $command = $connection->createCommand($sql2);
        $Detalleevento2 = $command->queryAll();
        $i=count($Detalleevento2);
        $j=count($Detalleevento);
        $t=$i-$j;
        ob_start();
        echo "<div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Eventos por Ejecutar</h3>
                  <p><h1>".$t."</p>
                </div>
                </div>
            <div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Eventos Ejecutados</h3>
                  <p><h1>".count($Detalleevento)."</h1></p>
                </div>
                </div>
                ";
                return ob_get_clean();

    }
    public function actionDetallecliente($fecha1,$fecha2)
    {
        $sqlcliente="sELECT * FROM cliente
                      INNER JOIN programa ON cliente.id_programa=programa.id_programa
                      INNER JOIN evento on programa.id_evento=evento.id_evento WHERE evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'";

        $sqlpagocliente="sELECT distinct pago.id_cliente FROM `pago`
                        INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                        INNER JOIN programa ON programa.id_programa=cliente.id_programa
                        INNER JOIN evento ON evento.id_evento=programa.id_evento WHERE pago.confirmacion=1 and evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'";

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sqlcliente);
        $cliente1 = $command->queryAll();


        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sqlpagocliente);
        $pagocliente = $command->queryAll();
        ob_start();
        echo "<div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Clientes Creados</h3>
                  <p><h1>".count($cliente1)."</h1></p>
                </div>
                </div>
            <div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Clientes Inscritos</h3>
                  <p><h1>".count($pagocliente)."</h1></p>
                </div>
                </div>
                ";
                return ob_get_clean();

    }
        public function actionDetallepago($fecha1,$fecha2)
    {
        $sql1="sELECT * FROM pago
                INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                INNER JOIN evento ON evento.id_evento=programa.id_evento
                WHERE pago.confirmacion=1 and evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'
                AND (pago.observaciones='1' or pago.observaciones='2')";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql1);
        $detallepago1 = $command->queryAll();

        $sql2="sELECT * FROM pago
                INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                INNER JOIN evento ON evento.id_evento=programa.id_evento
                WHERE pago.confirmacion=1 and evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'
                AND pago.observaciones = 0 AND pago.id_cliente not in
              (SELECT  pago.id_cliente FROM pago WHERE observaciones = 2)";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql2);
        $detallepago2 = $command->queryAll();
$pagostotalesrealizados=count($detallepago1)+count($detallepago2);
$sql11="sELECT * FROM pago
                INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                INNER JOIN evento ON evento.id_evento=programa.id_evento
                WHERE evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'
                and pago.confirmacion=1
                AND tipo_pago='electronico'";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql11);
        $detallepago11 = $command->queryAll();

        $sql12="sELECT * FROM pago
                INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                INNER JOIN evento ON evento.id_evento=programa.id_evento
                WHERE evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'
                and pago.confirmacion=1
                AND tipo_pago='efectivo'";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql12);
        $detallepago12 = $command->queryAll();
        ob_start();
        echo "<div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Clientes Pago Total</h3>
                  <p><h1>".count($detallepago1)."</h1></p>
                </div>
                </div>
            <div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Cliente Pago Abono</h3>
                  <p><h1>".count($detallepago2)."</h1></p>
                </div>
                </div>
                <div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Clientes con Pago Electronico</h3>
                  <p><h1>".count($detallepago11)."</h1></p>
                </div>
                </div>
            <div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Cliente Con Pago Efectivo</h3>
                  <p><h1>".count($detallepago12)."</h1></p>
                </div>
                </div>
                ";
                return ob_get_clean();

    }
        public function actionDetalleenlace($fecha1,$fecha2)
    {

        $sql1="sELECT * FROM enlace
                INNER JOIN cliente ON cliente.id_cliente=enlace.id_cliente
                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                INNER JOIN evento ON evento.id_evento=programa.id_evento
                WHERE evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'
                AND enlace.enviado=1";

        $sql2="sELECT * FROM enlace
                INNER JOIN cliente ON cliente.id_cliente=enlace.id_cliente
                INNER JOIN programa ON programa.id_programa=cliente.id_programa
                INNER JOIN evento ON evento.id_evento=programa.id_evento
                WHERE evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'
                AND enlace.enviado=0";

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql1);
        $detalleenlace1 = $command->queryAll();

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql2);
        $detalleenlace2 = $command->queryAll();
        ob_start();
        echo "<div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Paquetes Guardados</h3>
                  <p><h1>".count($detalleenlace1)."</h1></p>
                </div>
                </div>
            <div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <div class='tile-stats' style='height:100px;'>
                  <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
                  <div class='count'></div>
                  <h3>Paquetes Enviados</h3>
                  <p><h1>".count($detalleenlace2)."</h1></p>
                </div>
                </div>
                ";
                return ob_get_clean();

    }

    public function actionVentasdetalle($fecha1,$fecha2,$id_usuario)
{
  $sql1="sELECT sum(monto) as suma FROM pago
          INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
          INNER JOIN programa ON programa.id_programa=cliente.id_programa
          INNER JOIN evento ON evento.id_evento=programa.id_evento
          WHERE pago.confirmacion=1 and evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'
          AND pago.tipo_pago='efectivo'
          AND pago.id_usuario=".$id_usuario;
  $connection = Yii::$app->getDb();
  $command = $connection->createCommand($sql1);
  $detallepago1 = $command->queryAll();


  $sql2="sELECT sum(monto) as suma FROM pago
          INNER JOIN cliente ON cliente.id_cliente=pago.id_cliente
          INNER JOIN programa ON programa.id_programa=cliente.id_programa
          INNER JOIN evento ON evento.id_evento=programa.id_evento
          WHERE pago.confirmacion=1 and evento.fecha_evento BETWEEN '".$fecha1."' and '".$fecha2."'
          AND pago.tipo_pago='electronico'
          AND pago.id_usuario=".$id_usuario;
  $connection = Yii::$app->getDb();
  $command = $connection->createCommand($sql2);
  $detallepago2 = $command->queryAll();
  foreach($detallepago1 as $d1)
  {
    $deta1=$d1['suma'];
  }
  foreach($detallepago2 as $d2)
  {
    $deta2=$d2['suma'];
  }
  //var_dump($detallepago2);
    ob_start();
  echo "<div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
          <div class='tile-stats' style='height:100px;'>
            <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
            <div class='count'></div>
            <h3>Pagos en efectivo</h3>
            <p><h1>".$deta1."</h1></p>
          </div>
          </div>
      <div class='animated flipInY col-lg-12 col-md-12 col-sm-12 col-xs-12'>
          <div class='tile-stats' style='height:100px;'>
            <div class='icon'><i class='fa fa-sort-amount-desc'></i></div>
            <div class='count'></div>
            <h3>Pagos en electronico</h3>
            <p><h1>".$deta2."</h1></p>
          </div>
          </div>
          ";
          return ob_get_clean();

}
public function actionFotos()
{
  $model = new PagoFoto();
  return $this->render('fotos', [
            'model' => $model,
        ]);
}

public function actionFotocliente()
{

        $cedula = Yii::$app->request->post();
        $ci=$cedula['cedula'];
        $model = new Cliente();
        $modelevento = new Evento();
        $cliente=$model->find()
        ->joinWith('programa')
        ->innerjoin("evento","evento.id_evento=programa.id_evento")
        ->where(['cedula'=>$ci])
        //->andWhere(['evento.cerrado'=>'0'])
        ->one();
        foreach ($cliente->getPrograma()->all() as $k) {
            $id_programa=$k['id_programa'];
            $nombre_programa=$k['nombre_programa'];
            $id_evento=$k['id_evento'];
        }
        $evento=$modelevento->find()->where(['id_evento'=>$id_evento])->one();
        $fotos=FotoAdicional::find()->where(['id_cliente'=>$cliente->id_cliente])->one();
        //var_dump($fotos);

  Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
  return [
    "cedula" => $ci,
    "nombre" => $cliente->nombre,
    "id_cliente" => $cliente->id_cliente,
    "nombre_evento" => $evento->nombre_evento,
    "nombre_programa" => $nombre_programa,
    "cantidad_foto" => $fotos->cantidad_foto,
    "monto" => $fotos->monto,
    "total" => $fotos->total,
    "id_foto_adicional" => $fotos->id_foto,
  ];
}

public function actionFotocliente1()
{

        $cedula = Yii::$app->request->post();
        $ci=$cedula['cedula'];
        $model = new Cliente();
        $modelevento = new Evento();
        $cliente=$model->find()
        ->joinWith('programa')
        ->innerjoin("evento","evento.id_evento=programa.id_evento")
        ->where(['cedula'=>$ci])
        //->andWhere(['evento.cerrado'=>'0'])
        ->one();
        foreach ($cliente->getPrograma()->all() as $k) {
            $id_programa=$k['id_programa'];
            $nombre_programa=$k['nombre_programa'];
            $id_evento=$k['id_evento'];
        }
        $evento=$modelevento->find()->where(['id_evento'=>$id_evento])->one();
        //$fotos=FotoAdicional::find()->where(['id_cliente'=>$cliente->id_cliente])->one();
        //var_dump($fotos);

  Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
  return [
    //"cedula" => $ci,
    "nombre" => $cliente->nombre,
    "id_cliente" => $cliente->id_cliente,
    "nombre_evento" => $evento->nombre_evento,
    "nombre_programa" => $nombre_programa,
    //"cantidad_foto" => $fotos->cantidad_foto,
    //"monto" => $fotos->monto,
    //"total" => $fotos->total,
    //"id_foto_adicional" => $fotos->id_foto,
  ];
}

public function actionGuardarpago()
{

  $factura=mt_rand(5000,999999);
  $model= new PagoFoto();
  if(Yii::$app->request->post())
  {
    $model->load(Yii::$app->request->post());
  $model->numero_referencia=$factura;
  //$model->monto = $monto;
  //$model->id_cliente = $id_cliente;
  $model->observaciones = "1";
  $model->id_usuario = 2;
  $model->tipo_pago = "efectivo";
  $model->fecha_pago = date('y-m-d');
  $model->paquete = "1";
  $model->ref_payco = "0";
  $model->confirmacion = "0";
  $model->factura = "'".$factura."'";
  $model->ref_payco_corto = "0";
  $model->save();
  $client= new Cliente();
  $cliente=$client->find()->where(['id_cliente'=>$model->id_cliente])->one();
  return $this->render('view_foto', [
      'model' => $cliente,
  ]);
  }



}

}
