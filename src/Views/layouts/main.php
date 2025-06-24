<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Real Estate & Mortgage Demo' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="/" class="logo">HomeFinder Pro</a>
                <ul class="nav-links">
                    <li><a href="/listings">Browse Homes</a></li>
                    <li><a href="/listings/saved">Saved Homes</a></li>
                    <li><a href="/mortgage/preapproval">Get Pre-Approved</a></li>
                    <li><a href="/offers">My Offers</a></li>
                </ul>
            </div>
        </nav>
    </header>
    
    <main>
        <?= $content ?>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 Real Estate & Mortgage Demo. All rights reserved.</p>
            <p>
                User Session: <?= substr($_SESSION['user_id'] ?? '', 0, 8) ?>... | 
                Storage Mode: <?php 
                    $db = \App\Config\Database::getInstance();
                    $mode = $db->getStorageMode();
                    echo $mode === 'mongodb' ? '🗄️ MongoDB' : '🌐 Browser Session';
                ?>
            </p>
        </div>
    </footer>
</body>
</html>