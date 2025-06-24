<?php
namespace App\Models;

class Listing extends BaseModel {
    protected string $collectionName = 'listings';
    
    public function searchListings(array $filters = []) {
        $query = [];
        
        if (!empty($filters['city'])) {
            $query['address.city'] = ['$regex' => $filters['city'], '$options' => 'i'];
        }
        
        if (!empty($filters['min_price'])) {
            $query['price']['$gte'] = (int)$filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $query['price']['$lte'] = (int)$filters['max_price'];
        }
        
        if (!empty($filters['beds'])) {
            $query['beds']['$gte'] = (int)$filters['beds'];
        }
        
        $query['status'] = 'Active';
        
        return $this->find($query);
    }
    
    public function getListingById(string $listingId) {
        return $this->findOne(['listing_id' => $listingId]);
    }
    
    public function loadMockData() {
        $existingCount = $this->count();
        if ($existingCount > 0) {
            return false;
        }
        
        $mockData = json_decode(file_get_contents(__DIR__ . '/../../data/mock_listings.json'), true);
        
        foreach ($mockData as $listing) {
            $this->insertOne($listing);
        }
        
        return count($mockData);
    }
}