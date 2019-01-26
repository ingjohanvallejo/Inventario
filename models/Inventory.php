<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Inventory extends ActiveRecord {

    // Se realiza el metodo para incluir la base de datos 
    public static function getDb() {
        return Yii::$app->db;
    }

    public static function tableName() {
        return 'inventory';
    }

    public function rules()
    {
        return [
            [['fk_id_product', 'stock', 'is_inventory'], 'required'],
            [['fk_id_product', 'is_inventory'], 'integer'],
            [['stock'], 'number'],
            [['fk_id_product'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['fk_id_product' => 'id_product']],
        ];
    }

    public function getFkIdProduct()
    {
        return $this->hasOne(Product::className(), ['id_product' => 'fk_id_product']);
    }
}