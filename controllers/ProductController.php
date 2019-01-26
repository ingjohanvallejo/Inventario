<?php

namespace app\controllers;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use app\models\Product;
use app\models\ProductForm;
use app\models\SearchForm;
use app\models\Category;
use app\models\Inventory;
use app\models\InventoryForm;



class ProductController extends Controller {

    // Esta funcion tiene como objetivo listar los productos
    public function actionView() {

        //Se obtienen todos los resultados de productos
        $form = new SearchForm;
        $search = null;

        if ($form->load(Yii::$app->Request->get())){
            if ($form->validate()){
                $search = Html::encode($form->q);
                $table = Product::find()
                    ->where(["like","name_product",$search]);
                $count = clone $table;
                $pages = new Pagination([
                    "pageSize"=>3,
                    "totalCount"=>$count->count()
                ]);
                $model = $table->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
                
            } else {
                $from->getErrors();
            }
        } else {
            $table = Product::find();
            $count = clone $table;
            $pages = new Pagination([
                "pageSize"=>3,
                "totalCount"=>$count->count()
            ]);
            $model = $table->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();

        }

        return $this->render("view", ["model"=>$model, "form"=>$form, "search"=>$search, "pages"=>$pages,]);
    }

    // Esta funcion tiene como objetivo crear un nuevo producto
    public function actionCreate() {
        $model = new ProductForm;
        $msg = null;
        $categories = ArrayHelper::map(Category::find()->all(), 'id_category', 'name_category');

        if($model->load(Yii::$app->request->post())){

            if($model->validate()){
                $table = new Product;
                $tableInventory = new Inventory;

                $table->name_product = $model->name;
                $table->fk_id_category = $model->category;

                if($table->insert()){
                    //Se borran los campos del formulario
                    $table->name_product = null;
                    $table->fk_id_category = null;

                    $tableInventory->fk_id_product = $table->id_product;
                    $tableInventory->stock = 0;
                    $tableInventory->is_inventory = 0;

                    if($tableInventory->insert()){
                        echo "Inventario procesando exitosamente";
                    } else {
                        echo "Error procesando el inventario";
                    }

                    $msg = "Se creo exitosamente";
                    echo "<meta http-equiv='refresh' content='1; " .Url::toRoute("product/view"). "'>";
                } else {
                    $msg ="Error";
                }

            } else {
                //Si no se encuentra bien creado se gestiona el error
                $model->getErrors();
            }

        }
        return $this->render("create", ['model'=>$model, 'msg'=>$msg, 'categories'=>$categories]);
    }

    //Esta funcion tiene como objetivo editar un producto
    public function actionUpdate() {

        $model = new ProductForm;
        $msg = null;
        $categories = ArrayHelper::map(Category::find()->all(), 'id_category', 'name_category');

        if( $model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $table = Product::findOne($model->id_product);
                var_dump($table);
                    die();
                if($table) {
                    //Se realiza la actualización
                    $table->name_product = $model->name;
                    $table->fk_id_category = $model->category;
                    if($table->update()){
                        $msg = "Se actualizo exitosamente";
                        echo "<meta http-equiv='refresh' content='1; " .Url::toRoute("product/view"). "'>";
                    } else {
                        $msg ="El producto no se pudo actualizar";
                    }
                } else {
                    $msg="Error Actualizando";
                }

            } else {
                //Manejo de errores
                $model->getErrors();
            }

        }
        
        if(Yii::$app->request->get("id_product")) {

            $id_product = Html::encode($_GET["id_product"]);
            if($id_product) {
                $table = Product::findOne($id_product);
                //Se comprueba que el registro exista
                if( $table ){
                    $model->id_product = $table->id_product;
                    $model->name = $table->name_product;
                    $model->category = $table->fk_id_category;
                } else {
                    return $this->redirect(["product/view"]);
                }

            } else {
                return $this->redirect(["product/view"]);
            }

        } else {
            return $this->redirect(["product/view"]);
        }

        return $this->render("update", ["model"=>$model, "msg" =>$msg, 'categories'=>$categories]);
    }

    public function actionDelete() {

        if(Yii::$app->request->post()){
            $id_product = Html::encode($_POST["id_product"]);
            
            if(((int) $id_product)){
                $inventory =Inventory::find()->where(['fk_id_product' => $id_product])->one();

                if($inventory->is_inventory == 0){
                    if(Product::deleteAll("id_product=:id_product",[":id_product" => $id_product])){
                        echo "Eliminación exitosa";
                        echo "<meta http-equiv='refresh' content='2; " .Url::toRoute("product/view"). "'>";
                    } else {
                        echo "Ha ocurrido un error elimnando, redireccionando";
                        echo "<meta http-equiv='refresh' content='2; " .Url::toRoute("product/view"). "'>";
                    }
                } else {
                    echo "No se puede eliminar porque ya ha tenido inventarios";
                    echo "<meta http-equiv='refresh' content='2 " .Url::toRoute("product/view"). "'>";
                }
            } else {
                echo "Ha ocurrido un error, redireccionando";
                echo "<meta http-equiv='refresh' content='2; " .Url::toRoute("product/view"). "'>";
            }
        } else {
            return $this->redirect(["product/view"]);
        }

    }

    public function actionInventory() {
        $model = new InventoryForm;
        $msg = null;

        if( $model->load(Yii::$app->request->post())) {
            if($model->validate()){
                $id_product = Html::encode($_GET["id_product"]);
                $table =Inventory::find()->where(['fk_id_product' => $id_product])->one();
                if($table) {
                    //Se realiza la actualización
                    $table->fk_id_product = $model->id_product;
                    $table->stock = $model->stock;
                    $table->is_inventory = 1;
                    
                    if($table->update()){
                        $msg = "Se actualizo exitosamente";
                        echo "<meta http-equiv='refresh' content='1; " .Url::toRoute("product/view"). "'>";
                    } else {
                        $msg ="El stock no se pudo actualizar";
                    }
                } else {
                    $msg="Error Actualizando";
                }

            } else {

                //Manejo de errores
                $model->getErrors();
            }

        }

        if(Yii::$app->request->get('id_product')){
            $id_product = Html::encode($_GET["id_product"]);
            if((int) $id_product){
                $table =Inventory::find()->where(['fk_id_product' => $id_product])->one();
                if ($table){
                    $model->id_product = $table->fk_id_product;
                    $model->stock = $table->stock;
                    $model->is_inventory = $table->is_inventory;
                } else {
                    return $this->redirect(["product/view"]);
                }
            } else {

                return $this->redirect(["product/view"]);
            }

        } else {

            return $this->redirect(["product/view"]);
        }
        return $this->render("inventory",["model"=>$model, "msg"=>$msg]);
    }

}