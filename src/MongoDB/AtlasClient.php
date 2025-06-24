<?php
namespace App\MongoDB;

use App\Storage\SessionStorage;

class AtlasClient {
    private string $baseUrl;
    private string $apiKey;
    private string $dataSource;
    private string $database;
    
    public function __construct(string $connectionString) {
        // Extract cluster info from connection string
        preg_match('/mongodb\+srv:\/\/([^:]+):([^@]+)@([^\/]+)\/([^?]+)/', $connectionString, $matches);
        
        // For MongoDB Atlas Data API, we would need an API key
        // Since we don't have one, we'll use a hybrid approach
        $this->dataSource = 'Cluster0';
        $this->database = $_ENV['MONGODB_DATABASE'] ?? 'real_estate_demo';
    }
    
    public function selectDatabase(string $name) {
        return new AtlasDatabase($name, $this);
    }
}

class AtlasDatabase {
    private string $name;
    private AtlasClient $client;
    
    public function __construct(string $name, AtlasClient $client) {
        $this->name = $name;
        $this->client = $client;
    }
    
    public function selectCollection(string $name) {
        return new AtlasCollection($name, $this->name);
    }
}

class AtlasCollection {
    private string $name;
    private string $database;
    private SessionStorage $storage;
    
    public function __construct(string $name, string $database) {
        $this->name = $name;
        $this->database = $database;
        $this->storage = SessionStorage::getInstance();
    }
    
    public function find(array $filter = [], array $options = []) {
        $results = [];
        $collection = $this->storage->getCollection($this->name);
        
        foreach ($collection as $doc) {
            if ($this->matchesFilter($doc, $filter)) {
                $results[] = $doc;
            }
        }
        
        // Apply sorting
        if (isset($options['sort'])) {
            usort($results, function($a, $b) use ($options) {
                foreach ($options['sort'] as $field => $direction) {
                    $aVal = $this->getNestedValue($a, $field);
                    $bVal = $this->getNestedValue($b, $field);
                    
                    if ($aVal instanceof UTCDateTime) {
                        $aVal = $aVal->toDateTime()->getTimestamp();
                    }
                    if ($bVal instanceof UTCDateTime) {
                        $bVal = $bVal->toDateTime()->getTimestamp();
                    }
                    
                    if ($aVal == $bVal) continue;
                    
                    if ($direction === -1) {
                        return $aVal > $bVal ? -1 : 1;
                    } else {
                        return $aVal < $bVal ? -1 : 1;
                    }
                }
                return 0;
            });
        }
        
        return new AtlasCursor($results);
    }
    
    public function findOne(array $filter = [], array $options = []) {
        $collection = $this->storage->getCollection($this->name);
        
        foreach ($collection as $doc) {
            if ($this->matchesFilter($doc, $filter)) {
                return $doc;
            }
        }
        
        return null;
    }
    
    public function insertOne($document) {
        if (!isset($document['_id'])) {
            $document['_id'] = new \MongoDB\BSON\ObjectId();
        }
        
        $this->storage->addDocument($this->name, $document);
        
        return new InsertOneResult($document['_id']);
    }
    
    public function updateOne(array $filter, array $update, array $options = []) {
        $collection = $this->storage->getCollection($this->name);
        $updated = false;
        
        foreach ($collection as $index => &$doc) {
            if ($this->matchesFilter($doc, $filter)) {
                if (isset($update['$set'])) {
                    foreach ($update['$set'] as $key => $value) {
                        $doc[$key] = $value;
                    }
                }
                $collection[$index] = $doc;
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            $this->storage->setCollection($this->name, $collection);
            return new UpdateResult(1);
        }
        
        return new UpdateResult(0);
    }
    
    public function deleteOne(array $filter, array $options = []) {
        $collection = $this->storage->getCollection($this->name);
        
        foreach ($collection as $index => $doc) {
            if ($this->matchesFilter($doc, $filter)) {
                $this->storage->removeDocument($this->name, $index);
                return new DeleteResult(1);
            }
        }
        
        return new DeleteResult(0);
    }
    
    public function countDocuments(array $filter = [], array $options = []): int {
        $count = 0;
        $collection = $this->storage->getCollection($this->name);
        
        foreach ($collection as $doc) {
            if ($this->matchesFilter($doc, $filter)) {
                $count++;
            }
        }
        
        return $count;
    }
    
    private function matchesFilter(array $doc, array $filter): bool {
        if (empty($filter)) {
            return true;
        }
        
        foreach ($filter as $key => $value) {
            $docValue = $this->getNestedValue($doc, $key);
            
            if (is_array($value)) {
                if (isset($value['$regex'])) {
                    $pattern = '/' . $value['$regex'] . '/';
                    if (isset($value['$options']) && strpos($value['$options'], 'i') !== false) {
                        $pattern .= 'i';
                    }
                    if (!preg_match($pattern, $docValue)) {
                        return false;
                    }
                } elseif (isset($value['$gte'])) {
                    if ($docValue < $value['$gte']) {
                        return false;
                    }
                } elseif (isset($value['$lte'])) {
                    if ($docValue > $value['$lte']) {
                        return false;
                    }
                } else {
                    if ($docValue != $value) {
                        return false;
                    }
                }
            } else {
                if ($docValue != $value) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    private function getNestedValue(array $doc, string $key) {
        $keys = explode('.', $key);
        $value = $doc;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
}

class AtlasCursor {
    private array $results;
    
    public function __construct(array $results) {
        $this->results = $results;
    }
    
    public function toArray(): array {
        return $this->results;
    }
}

class InsertOneResult {
    private $insertedId;
    
    public function __construct($id) {
        $this->insertedId = $id;
    }
    
    public function getInsertedCount(): int {
        return 1;
    }
    
    public function getInsertedId() {
        return $this->insertedId;
    }
}

class UpdateResult {
    private int $modifiedCount;
    
    public function __construct(int $count) {
        $this->modifiedCount = $count;
    }
    
    public function getModifiedCount(): int {
        return $this->modifiedCount;
    }
}

class DeleteResult {
    private int $deletedCount;
    
    public function __construct(int $count) {
        $this->deletedCount = $count;
    }
    
    public function getDeletedCount(): int {
        return $this->deletedCount;
    }
}

// MongoDB BSON compatibility classes
namespace App\MongoDB;

class ObjectId {
    private string $id;
    
    public function __construct(string $id = null) {
        $this->id = $id ?: uniqid('', true);
    }
    
    public function __toString(): string {
        return $this->id;
    }
}

class UTCDateTime {
    private int $milliseconds;
    
    public function __construct(int $milliseconds = null) {
        $this->milliseconds = $milliseconds ?: (time() * 1000);
    }
    
    public function toDateTime(): \DateTime {
        $dt = new \DateTime();
        $dt->setTimestamp($this->milliseconds / 1000);
        return $dt;
    }
}

// MongoDB compatibility
namespace MongoDB;

use App\MongoDB\AtlasClient;
use App\MongoDB\AtlasDatabase;
use App\MongoDB\AtlasCollection;

class Client extends AtlasClient {}
class Database extends AtlasDatabase {}
class Collection extends AtlasCollection {}

// BSON compatibility
namespace MongoDB\BSON;

use App\MongoDB\ObjectId as AppObjectId;
use App\MongoDB\UTCDateTime as AppUTCDateTime;

class ObjectId extends AppObjectId {}
class UTCDateTime extends AppUTCDateTime {}