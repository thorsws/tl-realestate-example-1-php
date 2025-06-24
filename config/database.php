<?php
namespace App\Config;

use MongoDB\Client;
use App\MongoDB\AtlasClient;

class Database {
    private static ?Database $instance = null;
    private $client;
    private $database;
    private string $mode;
    
    private function __construct() {
        $this->mode = $_ENV['STORAGE_MODE'] ?? $_SERVER['STORAGE_MODE'] ?? 'session';
        
        try {
            if ($this->mode === 'mongodb' && extension_loaded('mongodb')) {
                // Use real MongoDB
                $mongoUri = $_ENV['MONGODB_URI'] ?? $_SERVER['MONGODB_URI'] ?? 'mongodb://localhost:27017';
                $mongoDb = $_ENV['MONGODB_DATABASE'] ?? $_SERVER['MONGODB_DATABASE'] ?? 'real_estate_demo';
                $this->client = new Client($mongoUri);
                $this->database = $this->client->selectDatabase($mongoDb);
            } else {
                // Use session-based storage
                $mongoUri = $_ENV['MONGODB_URI'] ?? $_SERVER['MONGODB_URI'] ?? '';
                $mongoDb = $_ENV['MONGODB_DATABASE'] ?? $_SERVER['MONGODB_DATABASE'] ?? 'real_estate_demo';
                $this->client = new AtlasClient($mongoUri);
                $this->database = $this->client->selectDatabase($mongoDb);
            }
        } catch (\Exception $e) {
            // Fallback to session storage if MongoDB fails
            $this->mode = 'session';
            $mongoUri = $_ENV['MONGODB_URI'] ?? $_SERVER['MONGODB_URI'] ?? '';
            $mongoDb = $_ENV['MONGODB_DATABASE'] ?? $_SERVER['MONGODB_DATABASE'] ?? 'real_estate_demo';
            $this->client = new AtlasClient($mongoUri);
            $this->database = $this->client->selectDatabase($mongoDb);
        }
    }
    
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getDatabase() {
        return $this->database;
    }
    
    public function getCollection(string $name) {
        return $this->database->selectCollection($name);
    }
    
    public function getStorageMode(): string {
        return $this->mode;
    }
}