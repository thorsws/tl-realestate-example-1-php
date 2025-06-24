<?php
namespace App\Storage;

class SessionStorage {
    private static ?SessionStorage $instance = null;
    
    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialize storage in session
        if (!isset($_SESSION['mongodb_data'])) {
            $_SESSION['mongodb_data'] = [
                'listings' => [],
                'saved_listings' => [],
                'preapproval_documents' => [],
                'offers' => []
            ];
            
            // Load mock listings data
            $mockData = json_decode(file_get_contents(__DIR__ . '/../../data/mock_listings.json'), true);
            $_SESSION['mongodb_data']['listings'] = $mockData;
        }
    }
    
    public static function getInstance(): SessionStorage {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getCollection(string $name): array {
        return $_SESSION['mongodb_data'][$name] ?? [];
    }
    
    public function setCollection(string $name, array $data): void {
        $_SESSION['mongodb_data'][$name] = $data;
    }
    
    public function addDocument(string $collection, array $document): void {
        if (!isset($_SESSION['mongodb_data'][$collection])) {
            $_SESSION['mongodb_data'][$collection] = [];
        }
        $_SESSION['mongodb_data'][$collection][] = $document;
    }
    
    public function removeDocument(string $collection, int $index): void {
        if (isset($_SESSION['mongodb_data'][$collection][$index])) {
            unset($_SESSION['mongodb_data'][$collection][$index]);
            $_SESSION['mongodb_data'][$collection] = array_values($_SESSION['mongodb_data'][$collection]);
        }
    }
}