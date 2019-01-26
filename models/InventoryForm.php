<?php

namespace app\models;
use Yii;
use yii\base\model;

class InventoryForm extends model{

public $id_product;
public $stock;
public $is_inventory;

    public function rules() {
  
        return [
            ['id_product', 'integer', 'message' => 'Id incorrecto'],
            ['stock', 'required', 'message' => 'Campo requerido'],
            //['is_inventory', 'required', 'message' => 'Campo requerido'],

        ];
    }
 
}