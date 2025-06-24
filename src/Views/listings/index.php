<div class="container">
    <div class="page-header">
        <h1>Browse Homes</h1>
        <p>Find your dream home from our curated selection</p>
    </div>
    
    <div class="filter-section">
        <form method="GET" action="/listings" class="filter-form">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?= htmlspecialchars($filters['city']) ?>" placeholder="e.g. Austin">
            </div>
            
            <div class="form-group">
                <label for="min_price">Min Price</label>
                <input type="number" id="min_price" name="min_price" value="<?= htmlspecialchars($filters['min_price']) ?>" placeholder="300000">
            </div>
            
            <div class="form-group">
                <label for="max_price">Max Price</label>
                <input type="number" id="max_price" name="max_price" value="<?= htmlspecialchars($filters['max_price']) ?>" placeholder="800000">
            </div>
            
            <div class="form-group">
                <label for="beds">Min Beds</label>
                <select id="beds" name="beds">
                    <option value="">Any</option>
                    <option value="1" <?= $filters['beds'] == '1' ? 'selected' : '' ?>>1+</option>
                    <option value="2" <?= $filters['beds'] == '2' ? 'selected' : '' ?>>2+</option>
                    <option value="3" <?= $filters['beds'] == '3' ? 'selected' : '' ?>>3+</option>
                    <option value="4" <?= $filters['beds'] == '4' ? 'selected' : '' ?>>4+</option>
                    <option value="5" <?= $filters['beds'] == '5' ? 'selected' : '' ?>>5+</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Search</button>
            <a href="/listings" class="btn btn-secondary">Clear</a>
        </form>
    </div>
    
    <?php if (empty($listings)): ?>
        <div class="empty-state">
            <h2>No homes found</h2>
            <p>Try adjusting your search filters</p>
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
                            <?php if ($listing['is_saved']): ?>
                                <form method="POST" action="/listings/unsave" style="display: inline;">
                                    <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">
                                    <button type="submit" class="btn btn-secondary">Unsave</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="/listings/save" style="display: inline;">
                                    <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">
                                    <button type="submit" class="btn">Save</button>
                                </form>
                            <?php endif; ?>
                            
                            <a href="/offers?listing_id=<?= $listing['listing_id'] ?>" class="btn">Make Offer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>