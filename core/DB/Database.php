<?php

use MongoDriver\Adapter;

class Database
{
    /**
     * @var Adapter $adapter;
     */
    private $adapter;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->adapter = new Adapter();
        $this->adapter->connect(CONNECTION_STRING);
        $this->adapter->selectDB(DBNAME);

        $this->init();
    }

    private function init() { }

    public function insert($model)
    {
        $reflector = new \PHPAnnotations\Reflection\Reflector($model);
        $collection = $reflector->getClass()->getAnnotation('\MongoDriver\Models\Model')->name;
        $this->adapter->insert($collection, $model);
    }

    public function findOne(&$model, $filters = [], $options = [])
    {
        $reflector = new \PHPAnnotations\Reflection\Reflector($model);
        $collection = $reflector->getClass()->getAnnotation('\MongoDriver\Models\Model')->name;
        $model = $this->adapter->findOne($collection, $filters, $options)[0];
    }
}