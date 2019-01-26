<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Product extends ActiveRecord {

    // Se realiza el metodo para incluir la base de datos 
    public static function getDb() {
        return Yii::$app->db;
    }

    public static function tableName() {
        return 'product';
    }

    public function rules()
    {
        return [
            [['name_product', 'fk_id_category'], 'required'],
            [['fk_id_category'], 'integer'],
            [['name_product'], 'string', 'max' => 50],
            [['fk_id_category'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['fk_id_category' => 'id_category']],
        ];
    }

    public function getFkIdCategory()
    {
        return $this->hasOne(Category::className(), ['id_category' => 'fk_id_category']);
    }
}