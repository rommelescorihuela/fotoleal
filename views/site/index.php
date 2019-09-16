<?php

/* @var $this yii\web\View */

$this->title = 'Fotografia Leal';
?>
<div class="site-index">

    <div class="jumbotron" style="text-align: center;">
        
        <h1>Bienvenido</h1>
    </div>
	<?php if (Yii::$app->session->hasFlash('error')){ ?>
    <div class="alert alert-danger alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h3><?= Yii::$app->session->getFlash('error'); }?></h3>
    </div>
    <div class="body-content">

    </div>
</div>
