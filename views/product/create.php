<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>

<a href="<?= Url::toRoute("product/view") ?>">Ir a la lista de Productos</a>

<h1>Crear Producto</h1>
<h3><?= $msg ?></h3>
<?php $form = ActiveForm::begin([
    "method" => "post",
    'enableClientValidation' => true,
]);
?>
<div class="form-group">
 <?= $form->field($model, "name")->input("text") ?>   
</div>

<div class="form-group">
<?=$form->field($model, 'category')->dropDownList($categories, ['prompt' => 'Seleccione Uno' ]);?>
</div>

<?= Html::submitButton("Crear", ["class" => "btn btn-primary"]) ?>

<?php $form->end() ?>