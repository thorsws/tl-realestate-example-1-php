<?php
namespace App\Models;

class PreApproval extends BaseModel {
    protected string $collectionName = 'preapproval_documents';
    
    public function createPreApproval(array $data) {
        $document = [
            'user_id' => $data['user_id'],
            'personal_info' => [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone']
            ],
            'financial_info' => [
                'annual_income' => (int)$data['annual_income'],
                'employment_status' => $data['employment_status'],
                'credit_score' => (int)$data['credit_score'],
                'down_payment' => (int)$data['down_payment'],
                'monthly_debts' => (int)$data['monthly_debts']
            ],
            'loan_info' => [
                'requested_amount' => (int)$data['requested_amount'],
                'loan_type' => $data['loan_type'],
                'loan_term' => $data['loan_term']
            ],
            'approval_details' => $this->calculateApproval($data),
            'created_at' => new \MongoDB\BSON\UTCDateTime(),
            'status' => 'approved'
        ];
        
        return $this->insertOne($document);
    }
    
    private function calculateApproval(array $data) {
        $income = (int)$data['annual_income'];
        $creditScore = (int)$data['credit_score'];
        $downPayment = (int)$data['down_payment'];
        $monthlyDebts = (int)$data['monthly_debts'];
        $requestedAmount = (int)$data['requested_amount'];
        
        $monthlyIncome = $income / 12;
        $maxMonthlyPayment = ($monthlyIncome * 0.28) - $monthlyDebts;
        
        $rates = [
            '30year' => 0.0625,
            '15year' => 0.0575,
            '5year' => 0.0525
        ];
        
        $rate = $rates[$data['loan_term']] ?? 0.0625;
        $monthlyRate = $rate / 12;
        $termMonths = match($data['loan_term']) {
            '15year' => 180,
            '5year' => 60,
            default => 360
        };
        
        $maxLoan = $maxMonthlyPayment * ((1 - pow(1 + $monthlyRate, -$termMonths)) / $monthlyRate);
        
        $approvedAmount = min($maxLoan, $requestedAmount * 1.1);
        
        if ($creditScore < 620) {
            $approvedAmount *= 0.8;
        } elseif ($creditScore < 700) {
            $approvedAmount *= 0.9;
        }
        
        $monthlyPayment = $approvedAmount * ($monthlyRate / (1 - pow(1 + $monthlyRate, -$termMonths)));
        
        return [
            'approved_amount' => round($approvedAmount),
            'interest_rate' => $rate,
            'monthly_payment' => round($monthlyPayment),
            'loan_term' => $data['loan_term'],
            'down_payment_required' => $downPayment,
            'total_home_price' => round($approvedAmount + $downPayment)
        ];
    }
    
    public function getUserPreApprovals(string $userId) {
        return $this->find(['user_id' => $userId], ['sort' => ['created_at' => -1]]);
    }
}