<?php
namespace App\Models;

use App\Config\Database;

abstract class BaseModel {
    protected $collection;
    protected string $collectionName;
    
    public function __construct() {
        $db = Database::getInstance();
        $this->collection = $db->getCollection($this->collectionName);
    }
    
    public function find(array $filter = [], array $options = []) {
        return $this->collection->find($filter, $options)->toArray();
    }
    
    public function findOne(array $filter = [], array $options = []) {
        return $this->collection->findOne($filter, $options);
    }
    
    public function insertOne(array $document) {
        return $this->collection->insertOne($document);
    }
    
    public function updateOne(array $filter, array $update, array $options = []) {
        return $this->collection->updateOne($filter, $update, $options);
    }
    
    public function deleteOne(array $filter, array $options = []) {
        return $this->collection->deleteOne($filter, $options);
    }
    
    public function count(array $filter = [], array $options = []) {
        return $this->collection->countDocuments($filter, $options);
    }
}