<div class="container">
    <div class="page-header">
        <h1>Make an Offer</h1>
        <p>Submit your offer for this property</p>
    </div>
    
    <?php if ($listing): ?>
        <div class="form-container" style="max-width: 800px;">
            <div style="display: flex; gap: 2rem; margin-bottom: 2rem;">
                <img src="<?= $listing['photos'][0] ?>" alt="Property" style="width: 200px; height: 150px; object-fit: cover; border-radius: 8px;">
                <div>
                    <h3><?= htmlspecialchars($listing['address']['street']) ?></h3>
                    <p><?= htmlspecialchars($listing['address']['city']) ?>, <?= $listing['address']['state'] ?> <?= $listing['address']['zip'] ?></p>
                    <p style="font-size: 1.5rem; color: #27ae60; font-weight: bold;">
                        Asking Price: $<?= number_format($listing['price']) ?>
                    </p>
                </div>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ($preApproval): ?>
                <div class="alert alert-success">
                    You're pre-approved for up to $<?= number_format($preApproval['approval_details']['approved_amount']) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/offers/create">
                <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">
                
                <h3>Offer Details</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="offer_price">Offer Price ($)</label>
                        <input type="number" id="offer_price" name="offer_price" 
                               value="<?= htmlspecialchars($formData['offer_price'] ?? $listing['price']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="financing_type">Financing Type</label>
                        <select id="financing_type" name="financing_type" required>
                            <option value="">Select...</option>
                            <option value="cash" <?= ($formData['financing_type'] ?? '') == 'cash' ? 'selected' : '' ?>>Cash</option>
                            <option value="conventional" <?= ($formData['financing_type'] ?? '') == 'conventional' ? 'selected' : '' ?>>Conventional Loan</option>
                            <option value="fha" <?= ($formData['financing_type'] ?? '') == 'fha' ? 'selected' : '' ?>>FHA Loan</option>
                            <option value="va" <?= ($formData['financing_type'] ?? '') == 'va' ? 'selected' : '' ?>>VA Loan</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="down_payment_percent">Down Payment (%)</label>
                        <input type="number" id="down_payment_percent" name="down_payment_percent" min="0" max="100"
                               value="<?= htmlspecialchars($formData['down_payment_percent'] ?? '20') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="closing_date">Preferred Closing Date</label>
                        <input type="date" id="closing_date" name="closing_date" 
                               min="<?= date('Y-m-d', strtotime('+2 weeks')) ?>"
                               value="<?= htmlspecialchars($formData['closing_date'] ?? date('Y-m-d', strtotime('+30 days'))) ?>" required>
                    </div>
                </div>
                
                <h3>Contingencies</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <label>
                        <input type="checkbox" name="contingencies[]" value="inspection" 
                               <?= in_array('inspection', $formData['contingencies'] ?? ['inspection']) ? 'checked' : '' ?>>
                        Home Inspection
                    </label>
                    
                    <label>
                        <input type="checkbox" name="contingencies[]" value="appraisal"
                               <?= in_array('appraisal', $formData['contingencies'] ?? ['appraisal']) ? 'checked' : '' ?>>
                        Appraisal
                    </label>
                    
                    <label>
                        <input type="checkbox" name="contingencies[]" value="financing"
                               <?= in_array('financing', $formData['contingencies'] ?? ['financing']) ? 'checked' : '' ?>>
                        Financing
                    </label>
                    
                    <label>
                        <input type="checkbox" name="contingencies[]" value="sale_of_home"
                               <?= in_array('sale_of_home', $formData['contingencies'] ?? []) ? 'checked' : '' ?>>
                        Sale of Current Home
                    </label>
                </div>
                
                <h3>Your Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="buyer_name">Full Name</label>
                        <input type="text" id="buyer_name" name="buyer_name" 
                               value="<?= htmlspecialchars($formData['buyer_name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="buyer_email">Email</label>
                        <input type="email" id="buyer_email" name="buyer_email" 
                               value="<?= htmlspecialchars($formData['buyer_email'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="form-group full-width">
                    <label for="buyer_phone">Phone</label>
                    <input type="tel" id="buyer_phone" name="buyer_phone" 
                           value="<?= htmlspecialchars($formData['buyer_phone'] ?? '') ?>" required>
                </div>
                
                <div class="form-group full-width">
                    <label for="message">Message to Seller (Optional)</label>
                    <textarea id="message" name="message" rows="4"><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group full-width" style="margin-top: 2rem;">
                    <button type="submit" class="btn" style="width: 100%; padding: 1rem; font-size: 1.1rem;">
                        Submit Offer
                    </button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h2>Property not found</h2>
            <p>Please select a property from the listings to make an offer</p>
            <a href="/listings" class="btn">Browse Homes</a>
        </div>
    <?php endif; ?>
</div>