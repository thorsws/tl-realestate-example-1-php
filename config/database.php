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
        $this->mode = $_ENV['STORAGE_MODE'] ?? 'session';
        
        try {
            if ($this->mode === 'mongodb' && extension_loaded('mongodb')) {
                // Use real MongoDB
                $this->client = new Client($_ENV['MONGODB_URI'] ?? 'mongodb://localhost:27017');
                $this->database = $this->client->selectDatabase($_ENV['MONGODB_DATABASE'] ?? 'real_estate_demo');
            } else {
                // Use session-based storage
                $this->client = new AtlasClient($_ENV['MONGODB_URI'] ?? '');
                $this->database = $this->client->selectDatabase($_ENV['MONGODB_DATABASE'] ?? 'real_estate_demo');
            }
        } catch (\Exception $e) {
            // Fallback to session storage if MongoDB fails
            $this->mode = 'session';
            $this->client = new AtlasClient($_ENV['MONGODB_URI'] ?? '');
            $this->database = $this->client->selectDatabase($_ENV['MONGODB_DATABASE'] ?? 'real_estate_demo');
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