<div class="container">
    <div class="page-header">
        <h1>My Offers</h1>
        <p>Track your submitted property offers</p>
    </div>
    
    <?php if (empty($offers)): ?>
        <div class="empty-state">
            <h2>No offers submitted yet</h2>
            <p>Browse homes and submit offers to see them here</p>
            <a href="/listings" class="btn">Browse Homes</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?php foreach ($offers as $offer): ?>
                <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; gap: 2rem;">
                        <?php if ($offer['listing']): ?>
                            <img src="<?= $offer['listing']['photos'][0] ?>" alt="Property" 
                                 style="width: 150px; height: 100px; object-fit: cover; border-radius: 4px;">
                        <?php endif; ?>
                        
                        <div style="flex: 1;">
                            <?php if ($offer['listing']): ?>
                                <h3><?= htmlspecialchars($offer['listing']['address']['street']) ?></h3>
                                <p style="color: #666; margin-bottom: 1rem;">
                                    <?= htmlspecialchars($offer['listing']['address']['city']) ?>, 
                                    <?= $offer['listing']['address']['state'] ?> 
                                    <?= $offer['listing']['address']['zip'] ?>
                                </p>
                            <?php else: ?>
                                <h3>Property Information Unavailable</h3>
                            <?php endif; ?>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                <div>
                                    <strong>Offer Price:</strong><br>
                                    $<?= number_format($offer['offer_price']) ?>
                                </div>
                                
                                <?php if ($offer['listing']): ?>
                                    <div>
                                        <strong>Asking Price:</strong><br>
                                        $<?= number_format($offer['listing']['price']) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    <strong>Status:</strong><br>
                                    <span style="color: <?= $offer['status'] == 'pending' ? '#f39c12' : '#27ae60' ?>;">
                                        <?= ucfirst($offer['status']) ?>
                                    </span>
                                </div>
                                
                                <div>
                                    <strong>Submitted:</strong><br>
                                    <?php 
                                        if (is_object($offer['created_at']) && method_exists($offer['created_at'], 'toDateTime')) {
                                            echo date('M j, Y', $offer['created_at']->toDateTime()->getTimestamp());
                                        } else {
                                            echo date('M j, Y');
                                        }
                                    ?>
                                </div>
                                
                                <div>
                                    <strong>Financing:</strong><br>
                                    <?= ucfirst(str_replace('_', ' ', $offer['financing_type'])) ?>
                                </div>
                                
                                <div>
                                    <strong>Down Payment:</strong><br>
                                    <?= $offer['down_payment_percent'] ?>%
                                </div>
                            </div>
                            
                            <?php if (!empty($offer['contingencies'])): ?>
                                <div style="margin-top: 1rem;">
                                    <strong>Contingencies:</strong>
                                    <?= implode(', ', array_map('ucfirst', $offer['contingencies'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>