<?php
namespace App\Config;

class StorageConfig {
    public static function getStorageMode(): string {
        // Check if MongoDB extension is loaded
        if (extension_loaded('mongodb')) {
            return 'mongodb';
        }
        
        // Check if environment variable is set to force a mode
        if (isset($_ENV['STORAGE_MODE'])) {
            return $_ENV['STORAGE_MODE'];
        }
        
        // Default to session storage
        return 'session';
    }
    
    public static function isMongoDBAvailable(): bool {
        return extension_loaded('mongodb');
    }
}