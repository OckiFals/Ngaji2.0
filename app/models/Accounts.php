<?php namespace app\models;

use Ngaji\Database\ActiveRecord;

class Accounts extends ActiveRecord {

    public function __construct($className=__CLASS__) {
        parent::__construct();
        class_parents($className);
    }

    public function tableName() {
        return 'accounts';
    }

    public function attributes() {
        return array(
            'id' => [
                'integer',
                'auto_increment',
                'primary_key'
            ],
            'username' => 'varchar_80',
            'password' => 'varchar_80',
            'name' => 'integer'
        );
    }

    public function rules() {
        return array(
            'primary_key' => 'id',
            'belongs_to' => [
                'accounts@id' => 'account_id'
            ]
        );
    }
}