<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\data\Pagination;
use app\models\Inventory;

?>

<a href="<?= Url::toRoute("product/create") ?>">Nuevo Producto</a>

<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("product/view"),
    "enableClientValidation" => true,
]);
?>

<div class="form-group">
    <?= $f->field($form, "q")->input("search") ?>
</div>

<?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>

<?php $f->end() ?>

<h3><?= $search ?></h3>

<h3>Lista de Productos</h3>
<table class="table table-bordered">
    <tr>
        <th>Nombre</th>
        <th>Categoria</th>
        <th>Stock</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <?php foreach($model as $row): ?>
    <tr>
        <td><?= $row->name_product ?></td>
        <td><?= $row->fk_id_category ?></td>
        <td><?php $inventory = Inventory::find()->where(['fk_id_product' => $row->id_product])->one(); ?>
            <?= $inventory->stock?>
        </td>
        <td><a href="<?= Url::toRoute(["product/update", "id_product"=>$row->id_product]) ?>">Editar</a></td>
        <td><a href="<?= Url::toRoute(["product/inventory", "id_product"=>$row->id_product]) ?>">Inventario</td>
        <td> <?php if ($inventory->is_inventory == 0) {?>
                <a href="#" data-toggle="modal" data-target="#id_product_<?= $row->id_product ?>">Eliminar</a>
                <div class="modal fade" role="dialog" aria-hidden="true" id="id_product_<?= $row->id_product ?>">
                        <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">Eliminar Producto</h4>
                                </div>
                                <div class="modal-body">
                                        <p>¿Realmente deseas eliminar el Producto <?= $row->name_product ?>?</p>
                                </div>
                                <div class="modal-footer">
                                <?= Html::beginForm(Url::toRoute("product/delete"), "POST") ?>
                                        <input type="hidden" name="id_product" value="<?= $row->id_product ?>">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Eliminar</button>
                                <?= Html::endForm() ?>
                                </div>
                                </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

            <?php } ?>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<?= LinkPager::widget([
    "pagination" => $pages,
]);