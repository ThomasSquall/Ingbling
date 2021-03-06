<?php

namespace Ingbling\DB;

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
        $this->adapter->selectDB(DB_NAME);

        $this->init();
    }

    private function init()
    {
        $this->initExtensions();
        $this->initUserDefined();
    }

    private function initExtensions()
    {
        foreach (glob(EXTS_DIR . "**/collections/*.php") as $file)
        {
            require_once $file;

            $files = explode('/', $file);
            $model = explode(".php", $files[count($files) - 1])[0];
            $this->adapter->registerModel(new $model());
        }
    }

    private function initUserDefined()
    {
        foreach (glob(APP_DIR . "collections/*.php") as $file)
        {
            require_once $file;

            $files = explode('/', $file);
            $model = explode(".php", $files[count($files) - 1])[0];
            $this->adapter->registerModel(new $model());
        }
    }

    private function getCollection($model)
    {
        $reflector = new \PHPAnnotations\Reflection\Reflector($model);
        $collection = $reflector->getClass()->getAnnotation('\MongoDriver\Models\Model')->name;

        return $collection;
    }

    public function insert($model)
    {
        $collection = $this->getCollection($model);
        return $this->adapter->insert($collection, $model);
    }

    public function update($search, $update)
    {
        $collection = $this->getCollection($search);
        $this->adapter->update($collection, $search, $update);
    }

    public function findOne(&$model, $filters = [], $options = [])
    {
        $collection = $this->getCollection($model);
        $model = $this->adapter->findOne($collection, $filters, $options);
        $model = count($model) == 0 ? false : $model[0];
    }

    public function find($class, $filters = [], $options = [])
    {
        $collection = $this->getCollection($class);
        $models = $this->adapter->find($collection, $filters, $options);

        return $models;
    }
}