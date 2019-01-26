
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<a href="<?= Url::toRoute("product/view") ?>">Ir a la lista de productos</a>

<h1>Editar Cantidad de producto</h1>

<h3><?= $msg ?></h3>

<?php $form = ActiveForm::begin([
    "method" => "post",
    'enableClientValidation' => true,
]);
?>

<?= $form->field($model, "id_product")->input("hidden")->label(false) ?>

<div class="form-group">
 <?= $form->field($model, "stock")->input("text") ?>   
</div>


<?= Html::submitButton("Actualizar", ["class" => "btn btn-primary"]) ?>

<?php $form->end() ?>