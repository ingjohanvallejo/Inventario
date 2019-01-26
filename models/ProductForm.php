<?php

namespace app\models;
use Yii;
use yii\base\model;

class ProductForm extends model{

public $id_product;
public $name;
public $category;

    public function rules() {
  
        return [
            ['id_product', 'integer', 'message' => 'Id incorrecto'],
            ['name', 'required', 'message' => 'Campo requerido'],
            ['category', 'required', 'message' => 'Campo requerido'],

        ];
    }
 
}