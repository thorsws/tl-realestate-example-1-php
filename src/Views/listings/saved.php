<div class="container">
    <div class="page-header">
        <h1>Saved Homes</h1>
        <p>Your favorite properties in one place</p>
    </div>
    
    <?php if (empty($listings)): ?>
        <div class="empty-state">
            <h2>No saved homes yet</h2>
            <p>Start browsing and save homes you're interested in</p>
            <a href="/listings" class="btn">Browse Homes</a>
        </div>
    <?php else: ?>
        <div class="listing-grid">
            <?php foreach ($listings as $listing): ?>
                <div class="listing-card">
                    <img src="<?= $listing['photos'][0] ?>" alt="<?= htmlspecialchars($listing['address']['street']) ?>" class="listing-image">
                    
                    <div class="listing-details">
                        <div class="listing-price">$<?= number_format($listing['price']) ?></div>
                        <div class="listing-address">
                            <?= htmlspecialchars($listing['address']['street']) ?><br>
                            <?= htmlspecialchars($listing['address']['city']) ?>, <?= $listing['address']['state'] ?> <?= $listing['address']['zip'] ?>
                        </div>
                        
                        <div class="listing-features">
                            <span><?= $listing['beds'] ?> beds</span>
                            <span><?= $listing['baths'] ?> baths</span>
                            <span><?= number_format($listing['sqft']) ?> sqft</span>
                        </div>
                        
                        <p class="listing-description"><?= htmlspecialchars($listing['description']) ?></p>
                        
                        <div style="display: flex; gap: 0.5rem;">
                            <form method="POST" action="/listings/unsave" style="display: inline;">
                                <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                            
                            <a href="/offers?listing_id=<?= $listing['listing_id'] ?>" class="btn">Make Offer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>