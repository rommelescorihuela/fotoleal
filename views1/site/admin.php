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
use yii\bootstrap\Modal ;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use app\models\Cliente;
use app\models\Programa;
use app\models\EventoSearch;
use app\models\Evento;
use app\models\Pago;

/* @var $this yii\web\View */
\dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]);
$this->title = 'Fotografia Leal';
for($i=0;$i<count($clientemensualabono);$i++)
{
  if(isset($eventomensualmonto[$i]))
  {
    /*echo $clientemensualabono2[$i];
    echo $eventomensualmonto2[$i].'<br>';*/
    $montototal1[$i]=intval($clientemensualabono[$i])*intval($eventomensualmonto[$i]);
    $montototal2[$i]=intval($clientemensualabono2[$i])*intval($eventomensualmonto2[$i]);
    $montototal[$i]=intval($montototal1[$i])+intval($montototal2[$i]);
  }
  else
  {
    $montototal[$i]=0;
  }
}
for($i=0;$i<count($clientemensualabono);$i++)
{
  $montototaldiferencia[$i]=(intval($montototal[$i])-intval($pagoeventomensualabono[$i]));
}

?>
<div class="col-md-12">
    <?= Html::a('Actualizar pagos pendientes', ['cliente/consultaestado'], ['class' => 'btn btn-success']) ?>
  </div>
 <div class="" role="main">

          <div class="">
            <div class="row top_tiles">
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
        'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'autoclose' => true,
        'label' => 'fecha de nacimineto'
    ]
    ]);
?>
</div>
<div class="col-md-4">
<?php  echo Html::submitButton('Buscar', ['class' => 'btn btn-default', 'name' => 'buscar-button']) ?>
</div>
<?php ActiveForm::end(); ?>
</div>

              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-check-square-o"></i></div>
                  <div class="count"><?php echo count($evento) ?></div>
                  <h4>Eventos creados</h4>
                  <p align="right">
                  <?= Html::button('+ Info', [
                      'value' => Yii::$app->urlManager->createUrl('/site/detalleevento?fecha1='.$model->fecha1.'&&fecha2='.$model->fecha2),
                      'class' => 'btn btn-default',
                      'id' => 'BtnModalId',
                      'data-toggle'=> 'modal',
                      'data-target'=> '#your-modal',
                    ]) ?>
                  </p>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon" ><i class="fa fa-user-plus"></i></div>
                  <div class="count"><?php echo count($cliente1) ?></div>
                  <h4>Clientes creados</h4>
                  <p align="right">
                  <?= Html::button('+ Info', [
                      'value' => Yii::$app->urlManager->createUrl('/site/detallecliente?fecha1='.$model->fecha1.'&&fecha2='.$model->fecha2),
                      'class' => 'btn btn-default',
                      'id' => 'BtnModalId1',
                      'data-toggle'=> 'modal',
                      'data-target'=> '#cliente-modal',
                    ]) ?>
                  </p>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-money"></i></div>
                  <div class="count"><?php echo $pagostotalesrealizados; ?></div>
                  <h4>Clientes pagos o abonados</h4>
                  <p align="right">
                  <?= Html::button('+ Info', [
                      'value' => Yii::$app->urlManager->createUrl('/site/detallepago?fecha1='.$model->fecha1.'&&fecha2='.$model->fecha2),
                      'class' => 'btn btn-default',
                      'id' => 'BtnModalId2',
                      'data-toggle'=> 'modal',
                      'data-target'=> '#pago-modal',
                    ]) ?>
                  </p>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-photo"></i></div>
                  <div class="count"><?php echo count($clienteenlace) ?></div>
                  <h4>Paquetes electronicos</h4>
                  <p align="right">
                  <?= Html::button('+ Info', [
                      'value' => Yii::$app->urlManager->createUrl('/site/detalleenlace?fecha1='.$model->fecha1.'&&fecha2='.$model->fecha2),
                      'class' => 'btn btn-default',
                      'id' => 'BtnModalId3',
                      'data-toggle'=> 'modal',
                      'data-target'=> '#enlace-modal',
                    ]) ?>
                  </p>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                      <div class="demo-container" style="height:300px">
                        <div id="chart_plot_1" class="demo-placeholder"><canvas id="grafico1" class="grafico1"></canvas></div>
                      </div>
                      <div class="tiles">
                        <div class="col-md-4">
                          <span>Total de Clientes</span>
                          <h2><?php echo count($cliente1)." clientes" ?></h2>
                        <div id="chart_plot_0" class="demo-placeholder11"><canvas id="grafico2"></canvas></div>
                        </div>
                        <div class="col-md-4">
                          <span>Total de Eventos</span>
                          <h2><?php echo count($evento)." eventos" ?></h2>
                          <div id="chart_plot_0" class="demo-placeholder11"><canvas id="grafico3"></canvas></div>
                        </div>
                        <div class="col-md-4">
                          <span>Total de Pagos= <?php echo "$".($epay+$efectivo) ?></span>
                          <h4><?php echo "Electronico: $".$epay." -- Efectivo: $".$efectivo;?></h4>
                        <div id="chart_plot_0" class="demo-placeholder11"><canvas id="grafico4"></canvas></div>
                        </div>
                      </div>

                    </div>

                    <div class="col-md-3 col-sm-12 col-xs-12">
                      <div>
                        <div class="x_title">
                          <h2>Mejores perfiles</h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="vendedores">
                        <ul class="list-unstyled top_profiles scroll-view">
                          <?php for($i=0;$i<count($vend);$i++){
                          //var_dump($foto[$i]);
                            ?>
                            <li class="media event">


                                <?php
                                //var_dump($foto[$i]);
                                if($foto[$i]==1){
                                  echo "<a class='pull-left border-aero profile_thumb'>";
                                  echo   "<i class='fa fa-user gray'></i>";
                                  //echo $foto[$i];
                                }
                                else {
                                  echo "<a class='pull-left border-aero user-profile1'>";
                                  echo "<img src=../../".$foto[$i]." style='width: 100%;height: 100%;'>";
                                }?>


                              </a>
                              <div class="media-body">
                                <?= Html::button( $vendedormonto2nombre[$i], [
                                    'value' => Yii::$app->urlManager->createUrl('/site/ventasdetalle?fecha1='.$model->fecha1.'&&fecha2='.$model->fecha2.'&&id_usuario='.$id_vend[$i]),
                                    'class' => 'btn',
                                    'id' => 'Btnvendedor'.$id_vend[$i],
                                    'data-toggle'=> 'modal',
                                    'data-target'=> '#vendedor-modal'.$id_vend[$i],
                                  ]) ?>
                                <p><strong><?php echo "$".$vendedormonto2[$i]?> </strong>  </p>

                                <p> <small>Monto total de ventas realizadas</small>
                                </p>
                              </div>
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>



            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div class="row" style="border-bottom: 1px solid #E0E0E0; padding-bottom: 5px; margin-bottom: 5px;">
                      <div class="col-md-7" style="overflow:hidden;">
                      <div id="chart_plot_1" class="demo-placeholder" height="11" width="11" ><canvas id="grafico5" class="grafico5"></canvas></div>
                      </div>

                      <div class="col-md-5">
                          <?= $form->field($model, 'evento')->dropDownlist(Arrayhelper::map($evento,'id_evento','nombre_evento'),['prompt' => 'Seleccione']); ?>
                        <canvas id="grafico6" class="grafico6"></canvas>
                        <h5 align="center">Numero de clientes por vendedor</h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>



          <!--  <div class="row">
              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top Profiles <small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item One Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Two Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Two Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Two Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Three Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top Profiles <small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item One Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Two Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Two Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Two Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Three Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top Profiles <small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item One Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Two Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Two Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Two Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item Three Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                  </div>
                </div>
              </div>
            </div>-->
          </div>
          <?php
  //echo $sqlcliente;
  /*$dataProvider = new SqlDataProvider([
    'sql' => $sqlcliente,
  'totalCount' => count($cliente1),
  'pagination' => [
  'pageSize' => 10,
  ],
  'sort' => [
  'attributes' => [
    //'id_cliente',
    'cedula',
    'nombre',
    //'apellido',
    //'correo',
    'nombre_programa',
    'nombre_evento',
  ],
  ],
]);*/

  // returns an array of data rows
  //$searchModel = new EventoSearch();
  //$models = $dataProvider->getModels();
  ?>
          <?php Pjax::begin(); ?>
          <?= GridView::widget([
              'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
              'columns' => [
                  ['class' => 'yii\grid\SerialColumn'],
                  'cedula',
                  'nombre',
                  [

            'label'=>'Programa',
            'format'=>'text',//raw, html
             'filter'=> \yii\helpers\Html::activeTextInput($searchModel, 'nombre_programa',['class'=>'form-control']),
            'content'=>function($data){
              $programa=Programa::find()->where(["id_programa"=>$data->id_programa])->one();
                return $programa->nombre_programa;
            }
        ],
        [

            'label'=>'Evento',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activeTextInput($searchModel, 'nombre_evento',['class'=>'form-control']),
            'content'=>function($data){
              $programa=Programa::find()->where(["id_programa"=>$data->id_programa])->one();
              $evento=Evento::find()->where(["id_evento"=>$programa->id_evento])->one();
                return $evento->nombre_evento;
            }
        ],
        [

            'label'=>'Estatus',
            'format'=>'text',//raw, html
           // 'filter'=> \yii\helpers\Html::activeTextInput($searchModel, 'observaciones',['class'=>'form-control']),
           'filter'=> \yii\helpers\Html::activeDropDownList($searchModel, 'observaciones', [''=>'Seleccione',1=>'Pago total',0=>'abono'],['class'=>'form-control']) ,
            'content'=>function($data){
              //$programa=Programa::find()->where(["id_programa"=>$data->id_programa])->one();
              //$evento=Evento::find()->where(["id_evento"=>$programa->id_evento])->one();
              $pagoclientelista=Pago::find()
                                ->innerJoin('cliente','pago.id_cliente=cliente.id_cliente')
                                ->innerJoin('programa','cliente.id_programa=programa.id_programa')
                                ->innerJoin('evento','programa.id_evento=evento.id_evento')
                                ->where(['pago.id_cliente'=>$data->id_cliente,'pago.confirmacion'=>1])
                                ->all();

              $o='vacio';
                  foreach ($pagoclientelista as $k) {
                    $o.=$k['observaciones'];
                  }
                  //return $o;

                if($o=='vacio02' )
                {
                  return '<b style="color:green;">pago total</b>';
                }
                elseif ($o=='vacio1') {
                  return '<b style="color:green;">pago total</b>';
                }
                elseif ($o=='vacio0') {
                  return '<b style="color:orange;">abono</b>';
                }
                else {
                  return '<b style="color:red;">no existe pago</b>';
                }
            }
        ],
        [

            'label'=>'Paquete',
            'format'=>'text',//raw, html
            'filter'=> \yii\helpers\Html::activeDropDownList($searchModel, 'paquete', [''=>'Seleccione',1=>'paquete premium',0=>'paquete digital'],['class'=>'form-control']) ,
            'content'=>function($data){
              //$programa=Programa::find()->where(["id_programa"=>$data->id_programa])->one();
              //$evento=Evento::find()->where(["id_evento"=>$programa->id_evento])->one();
              $pagoclientelista=Pago::find()
                                ->innerJoin('cliente','pago.id_cliente=cliente.id_cliente')
                                ->innerJoin('programa','cliente.id_programa=programa.id_programa')
                                ->innerJoin('evento','programa.id_evento=evento.id_evento')
                                ->where(['pago.id_cliente'=>$data->id_cliente])
                                ->all();

              $o='paquete';
                  foreach ($pagoclientelista as $k) {
                    $o.=$k['paquete'];
                  }
                  //return $o;
                  if($o=='paquete1' )
                  {
                    return 'paquete premium';
                  }
                  elseif ($o=='paquete0') {
                    return 'paquete digital';
                  }
                  elseif ($o=='paquete') {
                    return '';
                  }


            }
        ],
                 // 'apellido',
                  //'correo',
                 // 'nombre_programa',
                  //'nombre_evento',

                  //['class' => 'yii\grid\ActionColumn'],
              ],
          ]);
          ?>
          <?php Pjax::end(); ?>
        </div>

        <!--/////////////////////jquery///////////////////////////////////-->
        <script>
        $(document).ready(function(){
          $("#buscarform-evento").change(function(){
              $.ajax({
               url: '<?php echo Yii::$app->request->baseUrl. '/index.php/site/dona' ?>',
               type: 'post',
               data: {
                         evento: $("#buscarform-evento").val() ,
                     },
               success: function (data) {
                 //window.myDoughnut.data.datasets[0].data[2] = data['evento']*50;
                 var randomColorFactor = function() {
                     return Math.round(Math.random() * 65);
                 };
                 var randomColor = function(opacity) {
                     return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.1') + ')';
                 };
                 for(i=0;i<=50;i++)
                 {
                 /////////////////borro datos de la dona////////////////
                 window.myDoughnut.data.labels.pop();
                 window.myDoughnut.data.datasets.forEach((dataset) => {
                 dataset.data.pop();
               });
             }
                 for(i=0;i<=data['nc'];i++)
                 {
                 /////////////agrego datos en la dona/////////////////////
                 window.myDoughnut.data.labels.push(data['vendedor'][i]);
                 window.myDoughnut.data.datasets.forEach((dataset) => {
                 dataset.data.push(data['cantidad_cliente'][i]);
                 dataset.backgroundColor.push(randomColor(0.75));
                    });
                  }

                 window.myDoughnut.update();
               }
          });
          });
        });
        </script>
        <!--/////////////////////////////pantallas modales////////////////////////////////-->
    <?php
        Modal::begin([
                'header' => 'Datos de los eventos',
                'id' => 'your-modal',
                'size' => 'modal-md',
            ]);
        echo "<div id='modalContent' style='height:250px;'></div>";
        Modal::end();
    ?>
			<script>
			$('#BtnModalId').click(function(e){
    e.preventDefault();
    $('#your-modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
   return false;
});
			</script>
          <?php
        Modal::begin([
                'header' => 'Datos de los clientes',
                'id' => 'cliente-modal',
                'size' => 'modal-md',
            ]);
        echo "<div id='modalclienteContent' style='height:250px;'></div>";
        Modal::end();
    ?>
      <script>
      $('#BtnModalId1').click(function(e){
    e.preventDefault();
    $('#cliente-modal').modal('show')
        .find('#modalclienteContent')
        .load($(this).attr('value'));
   return false;
});
      </script>
          <?php
        Modal::begin([
                'header' => 'Datos de los Pagos recibidos',
                'id' => 'pago-modal',
                'size' => 'pago-md',
            ]);
        echo "<div id='modalpagoContent' style='height:450px;'></div>";
        Modal::end();
    ?>
      <script>
      $('#BtnModalId2').click(function(e){
    e.preventDefault();
    $('#pago-modal').modal('show')
        .find('#modalpagoContent')
        .load($(this).attr('value'));
   return false;
});
      </script>
          <?php
        Modal::begin([
                'header' => 'Datos de los eventos',
                'id' => 'enlace-modal',
                'size' => 'modal-md',
            ]);
        echo "<div id='modalenlaceContent' style='height:250px;'></div>";
        Modal::end();
    ?>
      <script>
      $('#BtnModalId3').click(function(e){
    e.preventDefault();
    $('#enlace-modal').modal('show')
        .find('#modalenlaceContent')
        .load($(this).attr('value'));
   return false;
});
      </script>

      <?php
      for($i=0;$i<count($vend);$i++)
      {


    Modal::begin([
            'header' => 'Datos de vendedor',
            'id' => 'vendedor-modal'.$id_vend[$i],
            'size' => 'modal-md',
        ]);
    echo "<div id='modalvendedorContent$id_vend[$i]' style='height:250px;'></div>";
    Modal::end();
?>
  <script>
  $('#Btnvendedor<?= $id_vend[$i]?>').click(function(e){
e.preventDefault();
$('#vendedor-modal<?= $id_vend[$i]?>').modal('show')
    .find('#modalvendedorContent<?= $id_vend[$i]?>')
    .load($(this).attr('value'));
return false;
});
  </script>
<?php } ?>
      <!--////////////////////////////////////////////////////////////////////////////////////////-->
        <!-- jQuery -->
        <!--<script src="../../../vendor/bower-asset/gentelella/vendors/jquery/dist/jquery.min.js"></script>-->
        <!-- Chart.js -->
        <script src="../../../vendor/bower-asset/gentelella/vendors/Chart.js/dist/Chart.min.js"></script>
        <!-- jQuery Sparklines -->
<!-- DateJS -->
    <script src="../../../vendor/bower-asset/gentelella/vendors/DateJS/build/date.js"></script>
        <script src="../../../vendor/bower-asset/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

        <!-- Custom Theme Scripts -->

        <script src="../../../vendor/bower-asset/gentelella/vendors/Chart.js/dist/Chart.bundle.js"></script>
      <!--  <script src="/fotoleal/web/js/grafica1.js"></script>-->
        <script>
            var MONTHS = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

            var randomScalingFactor = function() {
                return Math.round(Math.random() * 200);
                //return 0;
            };
            var randomColorFactor = function() {
                return Math.round(Math.random() * 65);
            };
            var randomColor = function(opacity) {
                return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
            };

            var config = {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($meses);?>,
                    datasets: [{
                        label: "Clientes",
                        data: <?php echo json_encode($clientemensual);?>,
                        fill: false,
                        borderDash: [5, 5],
                    }, {
                        label: 'Eventos',
                        data: <?php echo json_encode($eventomensual);?>,
                         backgroundColor: "rgba(93,109,19,0.5)",
                    }, {
                        label: "Pagos",
                        data: <?php echo json_encode($pagomensual);?>,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    title:{
                        display:false,
                        text:'Chart.js Line Chart'
                    },
                    tooltips: {
                        mode: 'label',
                        callbacks: {

                        }
                    },
                    hover: {
                        mode: ''
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                show: true,
                                labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                show: true,
                                labelString: 'Value'
                            },
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: 50,
                            }
                        }]
                    }
                }
            };

            $.each(config.data.datasets, function(i, dataset) {
                dataset.borderColor = randomColor(0.4);
                dataset.backgroundColor = randomColor(0.5);
                dataset.pointBorderColor = randomColor(0.7);
                dataset.pointBackgroundColor = randomColor(0.5);
                dataset.pointBorderWidth = 1;
            });



        </script>

        <script>
     var MONTHS = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

     var randomScalingFactor = function() {
         return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
     };
     var randomColorFactor = function() {
         return Math.round(Math.random() * 25);
     };
     var randomColor = function() {
         return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.3|)';
     };

     var barChartData = {
         labels: <?php echo json_encode($meses);?>,
         datasets: [{
             label: 'clientes',
             backgroundColor: "rgba(96,143,174,0.9)",
             data: <?php echo json_encode($clientemensual);?>
         }]

     };
     var config2 = {
         type: 'bar',
         data: barChartData,
         options: {
             // Elements options apply to all of the options unless overridden in a dataset
             // In this case, we are setting the border of each bar to be 2px wide and green
             elements: {
                 rectangle: {
                     borderWidth: 2,
                     borderColor: 'rgb(0, 0, 0)',
                     borderSkipped: 'bottom'
                 }
             },
             responsive: true,
             legend: {
               display: false,
                 position: 'top',
             },
             title: {
                 display: true,
                 text: ''
             },
             scales: {
                 xAxes: [{
                     display: false,
                     scaleLabel: {
                         show: true,
                         labelString: 'Month'
                     }
                 }],
                 yAxes: [{
                     display: true,
                     scaleLabel: {
                         show: true,
                         labelString: 'Value'
                     },

                 }],
             }
         }
     }



 </script>
 <script>
var MONTHS = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

var randomScalingFactor = function() {
  return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
};
var randomColorFactor = function() {
  return Math.round(Math.random() * 25);
};
var randomColor = function() {
  return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.3|)';
};

var barChartData = {
  labels: <?php echo json_encode($meses);?>,
  datasets: [{
      label: 'Eventos',
      backgroundColor: "rgba(96,143,174,0.9)",
      data: <?php echo json_encode($eventomensual);?>
  }]

};
var config3 = {
  type: 'bar',
  data: barChartData,
  options: {
      // Elements options apply to all of the options unless overridden in a dataset
      // In this case, we are setting the border of each bar to be 2px wide and green
      elements: {
          rectangle: {
              borderWidth: 2,
              borderColor: 'rgb(0, 0, 0)',
              borderSkipped: 'bottom'
          }
      },
      responsive: true,
      legend: {
        display: false,
          position: 'top',
      },
      title: {
          display: false,
          text: ''
      },
      scales: {
          xAxes: [{
              display: false,
              scaleLabel: {
                  show: true,
                  labelString: 'Month'
              }
          }],
          yAxes: [{
              display: true,
              scaleLabel: {
                  show: true,
                  labelString: 'Value'
              },

          }],
      }
  }
}
</script>

<script>
var MONTHS =["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

var randomScalingFactor = function() {
 return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
};
var randomColorFactor = function() {
 return Math.round(Math.random() * 25);
};
var randomColor = function() {
 return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.3|)';
};

var barChartData = {
 labels: <?php echo json_encode($meses);?>,
 datasets: [{
     label: 'Pagos',
     backgroundColor: "rgba(96,143,174,0.9)",
     data: <?php echo json_encode($pagomensual);?>
 }]

};
var config4 = {
 type: 'bar',
 data: barChartData,
 options: {
     // Elements options apply to all of the options unless overridden in a dataset
     // In this case, we are setting the border of each bar to be 2px wide and green
     elements: {
         rectangle: {
             borderWidth: 2,
             borderColor: 'rgb(0, 0, 0)',
             borderSkipped: 'bottom'
         }
     },
     responsive: true,
     legend: {
       display: false,
         position: 'top',
     },
     title: {
         display: false,
         text: ''
     },
     scales: {
         xAxes: [{
             display: false,
             scaleLabel: {
                 show: true,
                 labelString: 'Month'
             }
         }],
         yAxes: [{
             display: true,
             scaleLabel: {
                 show: true,
                 labelString: 'Value'
             },

         }],
     }
 }
}
</script>

<script>
       var randomScalingFactor = function() {
           return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
       };
       var randomColorFactor = function() {
           return Math.round(Math.random() * 255);
       };
//+intval($pagoeventomensualtotalrecaudado[$i]['suma'])
       var barChartData = {
           labels: <?php echo json_encode($meses);?>,
           datasets: [{
               label: 'Monto recaudado',
               backgroundColor: "rgba(49,62,95,0.9)",
               data: <?php echo json_encode($montototalrecaudadomensual);?>
           }, {
               label: 'Monto por recaudar',
               backgroundColor: "rgba(151,187,205,0.5)",
               data: <?php echo json_encode($montototaldiferencia);?>

           }
         ]

       };
       var config5 = {
           type: 'bar',
           data: barChartData,
           options: {
               title:{
                   display:true,
                   text:"Dinero recaudado por mes"
               },
               tooltips: {
                   mode: 'label'
               },
               responsive: true,
               maintainAspectRatio: false,
               scales: {
                   xAxes: [{
                       stacked: true,
                   }],
                   yAxes: [{
                     display:true,
                       stacked: true
                   }]
               }
           }
       }



   </script>
   <script>
       var randomScalingFactor = function() {
           return Math.round(Math.random() * 100);
       };
       var randomColorFactor = function() {
           return Math.round(Math.random() * 255);
       };
       var randomColor = function(opacity) {
           return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
       };

       var config6 = {
           type: 'doughnut',
           data: {
               datasets: [{
                   data: [

                   ],
                   backgroundColor: [
                       "#D7464A",
                   ],
                   label: 'Dataset 1'
               }],
               labels: [

               ]
           },
           options: {
               responsive: true,
               maintainAspectRatio: false,
               legend: {
                 display:false,
                   position: 'top',
               },
               title: {
                   display: false,
                   text: 'Chart.js Doughnut Chart'
               },
               animation: {
                   animateScale: true,
                   animateRotate: true
               }
           }
       };

       </script>


 <script>
 window.onload = function() {
     var ctx = document.getElementById("grafico1").getContext("2d");
     window.myLine = new Chart(ctx, config);
     var ctx2 = document.getElementById("grafico2").getContext("2d");
     window.myBar = new Chart(ctx2, config2);
     var ctx3 = document.getElementById("grafico3").getContext("2d");
     window.myBar = new Chart(ctx3, config3);
     var ctx4 = document.getElementById("grafico4").getContext("2d");
     window.myBar = new Chart(ctx4, config4);
     var ctx5 = document.getElementById("grafico5").getContext("2d");
     window.myBar = new Chart(ctx5,config5);
     var ctx6 = document.getElementById("grafico6").getContext("2d");
     window.myDoughnut = new Chart(ctx6, config6);
 };
 </script>
