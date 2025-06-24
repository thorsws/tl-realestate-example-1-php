<?php
namespace App\Controllers;

use App\Models\Offer;
use App\Models\Listing;
use App\Models\PreApproval;

class OfferController {
    private Offer $offerModel;
    private Listing $listingModel;
    private PreApproval $preApprovalModel;
    
    public function __construct() {
        $this->offerModel = new Offer();
        $this->listingModel = new Listing();
        $this->preApprovalModel = new PreApproval();
    }
    
    public function index() {
        $listingId = $_GET['listing_id'] ?? '';
        $userId = $_SESSION['user_id'] ?? '';
        
        if ($listingId) {
            $listing = $this->listingModel->getListingById($listingId);
            $preApprovals = $this->preApprovalModel->getUserPreApprovals($userId);
            $latestPreApproval = !empty($preApprovals) ? $preApprovals[0] : null;
            
            $this->render('offers/create', [
                'title' => 'Make an Offer',
                'listing' => $listing,
                'preApproval' => $latestPreApproval
            ]);
        } else {
            $offers = $this->offerModel->getUserOffers($userId);
            
            foreach ($offers as &$offer) {
                $offer['listing'] = $this->listingModel->getListingById($offer['listing_id']);
            }
            
            $this->render('offers/index', [
                'title' => 'My Offers',
                'offers' => $offers
            ]);
        }
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /offers');
            exit;
        }
        
        $errors = $this->validateOfferData($_POST);
        
        if (!empty($errors)) {
            $listing = $this->listingModel->getListingById($_POST['listing_id'] ?? '');
            
            $this->render('offers/create', [
                'title' => 'Make an Offer',
                'errors' => $errors,
                'formData' => $_POST,
                'listing' => $listing
            ]);
            return;
        }
        
        $_POST['user_id'] = $_SESSION['user_id'] ?? '';
        $_POST['contingencies'] = $_POST['contingencies'] ?? [];
        
        $result = $this->offerModel->createOffer($_POST);
        
        if ($result->getInsertedCount() > 0) {
            $this->render('offers/success', [
                'title' => 'Offer Submitted',
                'offerId' => $result->getInsertedId()
            ]);
        } else {
            $listing = $this->listingModel->getListingById($_POST['listing_id'] ?? '');
            
            $this->render('offers/create', [
                'title' => 'Make an Offer',
                'errors' => ['An error occurred. Please try again.'],
                'formData' => $_POST,
                'listing' => $listing
            ]);
        }
    }
    
    private function validateOfferData(array $data): array {
        $errors = [];
        
        $required = [
            'listing_id' => 'Property',
            'offer_price' => 'Offer price',
            'buyer_name' => 'Your name',
            'buyer_email' => 'Your email',
            'buyer_phone' => 'Your phone',
            'financing_type' => 'Financing type',
            'down_payment_percent' => 'Down payment percentage',
            'closing_date' => 'Preferred closing date'
        ];
        
        foreach ($required as $field => $label) {
            if (empty($data[$field])) {
                $errors[] = "{$label} is required";
            }
        }
        
        if (!empty($data['buyer_email']) && !filter_var($data['buyer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address";
        }
        
        if (!empty($data['offer_price']) && !empty($data['listing_id'])) {
            $listing = $this->listingModel->getListingById($data['listing_id']);
            if ($listing) {
                $offerPrice = (int)$data['offer_price'];
                if ($offerPrice < $listing['price'] * 0.7) {
                    $errors[] = "Offer price seems too low (less than 70% of asking price)";
                }
            }
        }
        
        return $errors;
    }
    
    private function render(string $view, array $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . "/../Views/{$view}.php";
        $content = ob_get_clean();
        require __DIR__ . '/../Views/layouts/main.php';
    }
}