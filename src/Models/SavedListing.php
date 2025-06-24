<?php
namespace App\Models;

class SavedListing extends BaseModel {
    protected string $collectionName = 'saved_listings';
    
    public function saveListing(string $userId, string $listingId) {
        $existing = $this->findOne([
            'user_id' => $userId,
            'listing_id' => $listingId
        ]);
        
        if ($existing) {
            return false;
        }
        
        return $this->insertOne([
            'user_id' => $userId,
            'listing_id' => $listingId,
            'saved_at' => new \MongoDB\BSON\UTCDateTime()
        ]);
    }
    
    public function unsaveListing(string $userId, string $listingId) {
        return $this->deleteOne([
            'user_id' => $userId,
            'listing_id' => $listingId
        ]);
    }
    
    public function getUserSavedListings(string $userId) {
        return $this->find(['user_id' => $userId]);
    }
    
    public function isListingSaved(string $userId, string $listingId) {
        return $this->findOne([
            'user_id' => $userId,
            'listing_id' => $listingId
        ]) !== null;
    }
}