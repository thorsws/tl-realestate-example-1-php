<?php
namespace App\Controllers;

use App\Models\Listing;
use App\Models\SavedListing;

class ListingController {
    private Listing $listingModel;
    private SavedListing $savedListingModel;
    
    public function __construct() {
        $this->listingModel = new Listing();
        $this->savedListingModel = new SavedListing();
        
        $this->listingModel->loadMockData();
    }
    
    public function index() {
        $filters = [
            'city' => $_GET['city'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'beds' => $_GET['beds'] ?? ''
        ];
        
        $listings = $this->listingModel->searchListings($filters);
        
        $userId = $_SESSION['user_id'] ?? '';
        foreach ($listings as &$listing) {
            $listing['is_saved'] = $this->savedListingModel->isListingSaved($userId, $listing['listing_id']);
        }
        
        $this->render('listings/index', [
            'title' => 'Browse Homes',
            'listings' => $listings,
            'filters' => $filters
        ]);
    }
    
    public function save() {
        $userId = $_SESSION['user_id'] ?? '';
        $listingId = $_POST['listing_id'] ?? '';
        
        if ($userId && $listingId) {
            $this->savedListingModel->saveListing($userId, $listingId);
        }
        
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/listings'));
        exit;
    }
    
    public function unsave() {
        $userId = $_SESSION['user_id'] ?? '';
        $listingId = $_POST['listing_id'] ?? '';
        
        if ($userId && $listingId) {
            $this->savedListingModel->unsaveListing($userId, $listingId);
        }
        
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/listings'));
        exit;
    }
    
    public function saved() {
        $userId = $_SESSION['user_id'] ?? '';
        $savedListings = $this->savedListingModel->getUserSavedListings($userId);
        
        $listings = [];
        foreach ($savedListings as $saved) {
            $listing = $this->listingModel->getListingById($saved['listing_id']);
            if ($listing) {
                $listing['is_saved'] = true;
                $listings[] = $listing;
            }
        }
        
        $this->render('listings/saved', [
            'title' => 'Saved Homes',
            'listings' => $listings
        ]);
    }
    
    private function render(string $view, array $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . "/../Views/{$view}.php";
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layouts/main.php';
    }
}