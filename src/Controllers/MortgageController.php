<?php
namespace App\Controllers;

use App\Models\PreApproval;

class MortgageController {
    private PreApproval $preApprovalModel;
    
    public function __construct() {
        $this->preApprovalModel = new PreApproval();
    }
    
    public function preapproval() {
        $this->render('mortgage/preapproval', [
            'title' => 'Get Pre-Approved'
        ]);
    }
    
    public function calculate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /mortgage/preapproval');
            exit;
        }
        
        $errors = $this->validatePreApprovalData($_POST);
        
        if (!empty($errors)) {
            $this->render('mortgage/preapproval', [
                'title' => 'Get Pre-Approved',
                'errors' => $errors,
                'formData' => $_POST
            ]);
            return;
        }
        
        $_POST['user_id'] = $_SESSION['user_id'] ?? '';
        
        $result = $this->preApprovalModel->createPreApproval($_POST);
        
        if ($result->getInsertedCount() > 0) {
            $preApproval = $this->preApprovalModel->findOne(['_id' => $result->getInsertedId()]);
            
            $this->render('mortgage/approval-result', [
                'title' => 'Pre-Approval Results',
                'preApproval' => $preApproval
            ]);
        } else {
            $this->render('mortgage/preapproval', [
                'title' => 'Get Pre-Approved',
                'errors' => ['An error occurred. Please try again.'],
                'formData' => $_POST
            ]);
        }
    }
    
    private function validatePreApprovalData(array $data): array {
        $errors = [];
        
        $required = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'phone' => 'Phone',
            'annual_income' => 'Annual income',
            'employment_status' => 'Employment status',
            'credit_score' => 'Credit score',
            'down_payment' => 'Down payment',
            'monthly_debts' => 'Monthly debts',
            'requested_amount' => 'Requested loan amount',
            'loan_type' => 'Loan type',
            'loan_term' => 'Loan term'
        ];
        
        foreach ($required as $field => $label) {
            if (empty($data[$field])) {
                $errors[] = "{$label} is required";
            }
        }
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address";
        }
        
        if (!empty($data['credit_score'])) {
            $score = (int)$data['credit_score'];
            if ($score < 300 || $score > 850) {
                $errors[] = "Credit score must be between 300 and 850";
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