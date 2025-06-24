<?php
namespace App\Mock;

class MockDatabase {
    private static array $collections = [];
    
    public function __construct() {
        // Initialize collections with mock data
        if (empty(self::$collections['listings'])) {
            $mockData = json_decode(file_get_contents(__DIR__ . '/../../data/mock_listings.json'), true);
            self::$collections['listings'] = $mockData;
        }
        
        if (!isset(self::$collections['saved_listings'])) {
            self::$collections['saved_listings'] = [];
        }
        
        if (!isset(self::$collections['preapproval_documents'])) {
            self::$collections['preapproval_documents'] = [];
        }
        
        if (!isset(self::$collections['offers'])) {
            self::$collections['offers'] = [];
        }
    }
    
    public function selectCollection(string $name) {
        return new MockCollection($name, self::$collections);
    }
}

class MockCollection {
    private string $name;
    private $collections;
    
    public function __construct(string $name, array &$collections) {
        $this->name = $name;
        $this->collections = &$collections;
    }
    
    public function find(array $filter = [], array $options = []) {
        $data = $this->collections[$this->name] ?? [];
        $filtered = [];
        
        foreach ($data as $doc) {
            if ($this->matchesFilter($doc, $filter)) {
                $filtered[] = $doc;
            }
        }
        
        // Apply sorting
        if (isset($options['sort'])) {
            $filtered = $this->sortDocuments($filtered, $options['sort']);
        }
        
        return new MockCursor($filtered);
    }
    
    public function findOne(array $filter = [], array $options = []) {
        $data = $this->collections[$this->name] ?? [];
        
        foreach ($data as $doc) {
            if ($this->matchesFilter($doc, $filter)) {
                return $doc;
            }
        }
        
        return null;
    }
    
    public function insertOne(array $document) {
        if (!isset($this->collections[$this->name])) {
            $this->collections[$this->name] = [];
        }
        
        $document['_id'] = new MockObjectId();
        if (isset($document['created_at']) && $document['created_at'] instanceof MockUTCDateTime) {
            $document['created_at'] = $document['created_at'];
        }
        
        $this->collections[$this->name][] = $document;
        
        return new MockInsertResult($document['_id']);
    }
    
    public function updateOne(array $filter, array $update, array $options = []) {
        $data = &$this->collections[$this->name];
        
        foreach ($data as &$doc) {
            if ($this->matchesFilter($doc, $filter)) {
                if (isset($update['$set'])) {
                    foreach ($update['$set'] as $key => $value) {
                        $doc[$key] = $value;
                    }
                }
                return new MockUpdateResult(1);
            }
        }
        
        return new MockUpdateResult(0);
    }
    
    public function deleteOne(array $filter, array $options = []) {
        $data = &$this->collections[$this->name];
        
        foreach ($data as $index => $doc) {
            if ($this->matchesFilter($doc, $filter)) {
                unset($data[$index]);
                $this->collections[$this->name] = array_values($data);
                return new MockDeleteResult(1);
            }
        }
        
        return new MockDeleteResult(0);
    }
    
    public function countDocuments(array $filter = [], array $options = []) {
        $data = $this->collections[$this->name] ?? [];
        $count = 0;
        
        foreach ($data as $doc) {
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
            if ($key === '$regex' || $key === '$options') {
                continue;
            }
            
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
    
    private function sortDocuments(array $documents, array $sort): array {
        usort($documents, function($a, $b) use ($sort) {
            foreach ($sort as $field => $direction) {
                $aVal = $this->getNestedValue($a, $field);
                $bVal = $this->getNestedValue($b, $field);
                
                if ($aVal instanceof MockUTCDateTime) {
                    $aVal = $aVal->toDateTime()->getTimestamp();
                }
                if ($bVal instanceof MockUTCDateTime) {
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
        
        return $documents;
    }
}

class MockCursor {
    private array $data;
    
    public function __construct(array $data) {
        $this->data = $data;
    }
    
    public function toArray(): array {
        return $this->data;
    }
}

class MockInsertResult {
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

class MockUpdateResult {
    private int $modifiedCount;
    
    public function __construct(int $count) {
        $this->modifiedCount = $count;
    }
    
    public function getModifiedCount(): int {
        return $this->modifiedCount;
    }
}

class MockDeleteResult {
    private int $deletedCount;
    
    public function __construct(int $count) {
        $this->deletedCount = $count;
    }
    
    public function getDeletedCount(): int {
        return $this->deletedCount;
    }
}

class MockObjectId {
    private string $id;
    
    public function __construct() {
        $this->id = uniqid('', true);
    }
    
    public function __toString(): string {
        return $this->id;
    }
}

class MockUTCDateTime {
    private int $timestamp;
    
    public function __construct($milliseconds = null) {
        $this->timestamp = $milliseconds ? $milliseconds / 1000 : time();
    }
    
    public function toDateTime(): \DateTime {
        $dt = new \DateTime();
        $dt->setTimestamp($this->timestamp);
        return $dt;
    }
}

// MongoDB namespace mock classes
namespace MongoDB;

class Client {
    public function __construct(string $uri = '') {}
    
    public function selectDatabase(string $name): Database {
        return new Database($name);
    }
}

class Database {
    private string $name;
    
    public function __construct(string $name) {
        $this->name = $name;
    }
    
    public function selectCollection(string $name): Collection {
        return new Collection($name);
    }
}

class Collection {
    private string $name;
    
    public function __construct(string $name) {
        $this->name = $name;
    }
}

namespace MongoDB\BSON;

class ObjectId extends \App\Mock\MockObjectId {}
class UTCDateTime extends \App\Mock\MockUTCDateTime {}