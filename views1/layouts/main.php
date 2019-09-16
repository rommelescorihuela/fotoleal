<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

$bundle = yiister\gentelella\assets\Asset::register($this);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/fotoleal/vendor/bower-asset/gentelella/build/css/custom.min.css">
    <link rel="stylesheet" type="text/css" href="/fotoleal/web/css/personalizado.css">
    <script src="/fotoleal/vendor/bower-asset/gentelella/vendors/Chart.js/dist/Chart.js" type="text/javascript"></script>
    <script src="/fotoleal/vendor/bower-asset/gentelella/vendors/jquery/dist/jquery.js" type="text/javascript"></script>
</head>
<body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>" >
<?php $this->beginBody(); ?>
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col fotoleal-fondo">
            <div class="left_col scroll-view">

                <div id="divflotante" class="navbar nav_title" style="border: 0;">
                    <a href="/fotoleal/web" ><img src="/fotoleal/img/fotoleal.png" class="logo-leal"></a>
                </div>
                <div style="height:5%;"></div>

                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                      <?php $path=Yii::getAlias('@webroot');?>
                    <?php  if(!Yii::$app->user->isGuest)
                           {
                             $foto=Yii::$app->user->identity->foto;

                              echo"<img src=../../$foto class='img-circle profile_img'>";
                           }else{
                             echo"<img src='/fotoleal/web/icono/comprador.png' class='img-circle profile_img'>";
                           }?>


                    </div>
                    <div class="profile_info">
                        <span>Bienvenido,</span>
                        <h2><?php
                        if(!Yii::$app->user->isGuest)
                             {
                                echo Yii::$app->user->identity->usuario;
                             }else{echo "Invitado";}
                        ?></h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <h3>General</h3>
                        <?=
                        \yiister\gentelella\widgets\Menu::widget(
                            [
                            "items" => [
//["label" => "Inicio", "url" => ['/site/admin'], "icon" => "home"],
//,
!Yii::$app->user->isGuest ? ([]):(["label" => "ENTRAR", "url" => ['/site/login'], "icon" => "home"]),

Yii::$app->user->isGuest ? ([]):(Yii::$app->user->identity->id_rol==2?([]):(["label" => "TABLERO DE CONTROL", "url" => ['/site/admin'], "icon" => "id-card",])),
Yii::$app->user->isGuest ? ([]):(Yii::$app->user->identity->id_rol==2?([]):(["label" => "GESTION ADMINISTRATIVA", "url" => "#", "icon" => "id-card",
"items" => [
                    [
                        "label" => "EVENTO",
                        "url" => ['/evento/index'],
                    ],
                    [
                        "label" => "PROGRAMA",
                        "url" => ['/programa/index'],
                    ],
                    [
                        "label" => "CLIENTE",
                        "url" => ['/cliente/index'],
                    ],
                    [
                        "label" => "PAGOS",
                        "url" => ['/pago/index'],
                    ],
                ],
])),
Yii::$app->user->isGuest ? ([]):(Yii::$app->user->identity->id_rol==2?([]):(["label" => "OPCIONES DE ADMINISTRADOR", "url" => "#", "icon" => "id-card",
"items" => [
                    [
                        "label" => "ENVIO DE CORREO MASIVO",
                        "url" => ['/cliente/correo'],
                    ],
                    [
                        "label" => "LISTADO DE ENTREGA",
                        "url" => ['/cliente/envio'],
                    ],
                    [
                        "label" => "LISTADO DE PAGO",
                        "url" => ['/cliente/listadopago'],
                    ],
                ],
])),

//Yii::$app->user->isGuest ? ([]):(Yii::$app->user->identity->id_rol==2?([]):(["label" => "Pago", "url" => ['/pago/index'], "icon" => "id-card"])),
//Yii::$app->user->isGuest ? ([]):(Yii::$app->user->identity->id_rol==2?([]):(["label" => "Enlace", "url" => ['/enlace/index'], "icon" => "id-card"])),
//Yii::$app->user->isGuest ? ([]):(Yii::$app->user->identity->id_rol==2?([]):(["label" => "Envio", "url" => ['/envio/index'], "icon" => "id-card"])),
//Yii::$app->user->isGuest ? ([]):(Yii::$app->user->identity->id_rol==2?([]):(["label" => "Reporte", "url" => ['/cliente/reporte'], "icon" => "id-card"])),
//Yii::$app->user->isGuest ? ([]):(Yii::$app->user->identity->id_rol==2?([]):(["label" => "Mantenimiento", "url" => ['/cliente/importar'], "icon" => "id-card"])),
Yii::$app->user->isGuest ? ([]):(Yii::$app->user->identity->id_rol==2?([]):(
["label" => "CONFIGURACION","url" => "#","icon" => "table",
    "items" => [
                    [
                        "label" => "USUARIOS",
                        "url" => ['/usuario/index'],
                    ],
                    [
                        "label" => "PERFILES",
                        "url" => ['/rol/index'],
                    ],
                ],
            ])
),

                            ],
                            ]
                        )
                        ?>
                    </div>

                </div>
                <!-- /sidebar menu -->


                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu" id="divflotantemenu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <?php
                        if(!Yii::$app->user->isGuest)
                             {
                                ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <?php echo Yii::$app->user->identity->usuario; ?>
                        <?php  if(!Yii::$app->user->isGuest)
                               {
                                 $foto=Yii::$app->user->identity->foto;
                                 $path=Yii::getAlias('@webroot');
                                  echo"<img src=../../$foto>";
                               }else{
                                 echo'<img src="http://placehold.it/128x128" alt="..." class="img-circle profile_img">';
                               }?>

                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <?=
                                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout ',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
                                ?>
                            </ul>
                        </li>

                    </ul>
                <?php }else{} ?>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->

        <div class="right_col" role="main">
            <?php if (isset($this->params['h1'])): ?>
                <div class="page-title">
                    <div class="title_left">
                        <h1><?= $this->params['h1'] ?></h1>
                    </div>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
            <div style="height:5%;"></div>

            <?= $content ?>
        </div>
        <!-- /page content -->
        <!-- footer content -->
       <!-- <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com" rel="nofollow" target="_blank">Colorlib</a><br />
                Extension for Yii framework 2 by <a href="http://yiister.ru" rel="nofollow" target="_blank">Yiister</a>
            </div>
            <div class="clearfix"></div>
        </footer>-->
        <!-- /footer content -->
    </div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>
<!-- /footer content -->
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
