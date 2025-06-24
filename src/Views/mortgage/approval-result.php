<div class="container">
    <div class="page-header">
        <h1>Congratulations!</h1>
        <p>You're pre-approved for a mortgage</p>
    </div>
    
    <div class="form-container">
        <div class="approval-result">
            <h3>Your Pre-Approval Details</h3>
            
            <div style="text-align: center; margin: 2rem 0;">
                <p style="font-size: 1.2rem; color: #666;">You're approved for up to</p>
                <p style="font-size: 3rem; color: #27ae60; font-weight: bold; margin: 0.5rem 0;">
                    $<?= number_format($preApproval['approval_details']['approved_amount']) ?>
                </p>
                <p style="color: #666;">Total home price: $<?= number_format($preApproval['approval_details']['total_home_price']) ?></p>
            </div>
            
            <div style="background: white; padding: 1.5rem; border-radius: 8px; margin-top: 2rem;">
                <h4 style="margin-bottom: 1rem;">Loan Terms</h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <strong>Interest Rate:</strong><br>
                        <?= number_format($preApproval['approval_details']['interest_rate'] * 100, 2) ?>% APR
                    </div>
                    
                    <div>
                        <strong>Monthly Payment:</strong><br>
                        $<?= number_format($preApproval['approval_details']['monthly_payment']) ?>
                    </div>
                    
                    <div>
                        <strong>Loan Term:</strong><br>
                        <?= str_replace('year', ' Year', $preApproval['approval_details']['loan_term']) ?> Fixed
                    </div>
                    
                    <div>
                        <strong>Down Payment:</strong><br>
                        $<?= number_format($preApproval['approval_details']['down_payment_required']) ?>
                    </div>
                    
                    <div>
                        <strong>Loan Type:</strong><br>
                        <?= ucfirst($preApproval['loan_info']['loan_type']) ?>
                    </div>
                    
                    <div>
                        <strong>Pre-Approval Date:</strong><br>
                        <?php 
                            if (is_object($preApproval['created_at']) && method_exists($preApproval['created_at'], 'toDateTime')) {
                                echo date('F j, Y', $preApproval['created_at']->toDateTime()->getTimestamp());
                            } else {
                                echo date('F j, Y');
                            }
                        ?>
                    </div>
                </div>
            </div>
            
            <div style="background: #e3f2fd; padding: 1rem; border-radius: 4px; margin-top: 2rem;">
                <p style="margin: 0; color: #1976d2;">
                    <strong>Next Steps:</strong> This pre-approval is valid for 90 days. 
                    Start browsing homes within your budget and make an offer with confidence!
                </p>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem; justify-content: center;">
                <a href="/listings?max_price=<?= $preApproval['approval_details']['total_home_price'] ?>" class="btn">
                    Browse Homes in Your Budget
                </a>
                <a href="/listings/saved" class="btn btn-secondary">
                    View Saved Homes
                </a>
            </div>
        </div>
    </div>
</div>