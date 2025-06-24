<div class="container">
    <div class="page-header">
        <h1>Get Pre-Approved</h1>
        <p>Find out how much home you can afford in minutes</p>
    </div>
    
    <div class="form-container">
        <h2>Mortgage Pre-Approval Application</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/mortgage/calculate">
            <h3>Personal Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" 
                           value="<?= htmlspecialchars($formData['first_name'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" 
                           value="<?= htmlspecialchars($formData['last_name'] ?? '') ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?= htmlspecialchars($formData['phone'] ?? '') ?>" required>
                </div>
            </div>
            
            <h3>Financial Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="annual_income">Annual Income ($)</label>
                    <input type="number" id="annual_income" name="annual_income" 
                           value="<?= htmlspecialchars($formData['annual_income'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="employment_status">Employment Status</label>
                    <select id="employment_status" name="employment_status" required>
                        <option value="">Select...</option>
                        <option value="employed" <?= ($formData['employment_status'] ?? '') == 'employed' ? 'selected' : '' ?>>Employed</option>
                        <option value="self-employed" <?= ($formData['employment_status'] ?? '') == 'self-employed' ? 'selected' : '' ?>>Self-Employed</option>
                        <option value="retired" <?= ($formData['employment_status'] ?? '') == 'retired' ? 'selected' : '' ?>>Retired</option>
                        <option value="other" <?= ($formData['employment_status'] ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="credit_score">Credit Score</label>
                    <input type="number" id="credit_score" name="credit_score" min="300" max="850"
                           value="<?= htmlspecialchars($formData['credit_score'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="monthly_debts">Monthly Debts ($)</label>
                    <input type="number" id="monthly_debts" name="monthly_debts" 
                           value="<?= htmlspecialchars($formData['monthly_debts'] ?? '0') ?>" required>
                </div>
            </div>
            
            <h3>Loan Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="requested_amount">Requested Loan Amount ($)</label>
                    <input type="number" id="requested_amount" name="requested_amount" 
                           value="<?= htmlspecialchars($formData['requested_amount'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="down_payment">Down Payment ($)</label>
                    <input type="number" id="down_payment" name="down_payment" 
                           value="<?= htmlspecialchars($formData['down_payment'] ?? '') ?>" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="loan_type">Loan Type</label>
                    <select id="loan_type" name="loan_type" required>
                        <option value="">Select...</option>
                        <option value="conventional" <?= ($formData['loan_type'] ?? '') == 'conventional' ? 'selected' : '' ?>>Conventional</option>
                        <option value="fha" <?= ($formData['loan_type'] ?? '') == 'fha' ? 'selected' : '' ?>>FHA</option>
                        <option value="va" <?= ($formData['loan_type'] ?? '') == 'va' ? 'selected' : '' ?>>VA</option>
                        <option value="jumbo" <?= ($formData['loan_type'] ?? '') == 'jumbo' ? 'selected' : '' ?>>Jumbo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="loan_term">Loan Term</label>
                    <select id="loan_term" name="loan_term" required>
                        <option value="">Select...</option>
                        <option value="30year" <?= ($formData['loan_term'] ?? '') == '30year' ? 'selected' : '' ?>>30 Year Fixed</option>
                        <option value="15year" <?= ($formData['loan_term'] ?? '') == '15year' ? 'selected' : '' ?>>15 Year Fixed</option>
                        <option value="5year" <?= ($formData['loan_term'] ?? '') == '5year' ? 'selected' : '' ?>>5 Year ARM</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group full-width" style="margin-top: 2rem;">
                <button type="submit" class="btn" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                    Get Pre-Approved
                </button>
            </div>
        </form>
    </div>
</div>