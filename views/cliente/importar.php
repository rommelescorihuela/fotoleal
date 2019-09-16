<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($model, 'archivo')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Cargar datos', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>