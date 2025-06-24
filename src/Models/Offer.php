<?php
namespace App\Models;

class Offer extends BaseModel {
    protected string $collectionName = 'offers';
    
    public function createOffer(array $data) {
        $document = [
            'user_id' => $data['user_id'],
            'listing_id' => $data['listing_id'],
            'offer_price' => (int)$data['offer_price'],
            'buyer_info' => [
                'name' => $data['buyer_name'],
                'email' => $data['buyer_email'],
                'phone' => $data['buyer_phone']
            ],
            'contingencies' => $data['contingencies'] ?? [],
            'message' => $data['message'] ?? '',
            'financing_type' => $data['financing_type'],
            'down_payment_percent' => (int)$data['down_payment_percent'],
            'closing_date' => $data['closing_date'],
            'status' => 'pending',
            'created_at' => new \MongoDB\BSON\UTCDateTime()
        ];
        
        return $this->insertOne($document);
    }
    
    public function getUserOffers(string $userId) {
        return $this->find(['user_id' => $userId], ['sort' => ['created_at' => -1]]);
    }
    
    public function getOfferById(string $offerId) {
        return $this->findOne(['_id' => new \MongoDB\BSON\ObjectId($offerId)]);
    }
}