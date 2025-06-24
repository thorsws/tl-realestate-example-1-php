# Real Estate & Mortgage Demo Application

A PHP + MongoDB web application that simulates a homebuyer journey from browsing property listings to receiving mortgage pre-approval and submitting offers.

## Features

- **Property Listings**: Browse homes with filtering by city, price range, and bedrooms
- **Saved Listings**: Save favorite properties for later viewing
- **Mortgage Pre-Approval**: Get pre-approved with a simulated mortgage calculator
- **Make Offers**: Submit offers on properties with contingencies

## Requirements

- PHP 8.0 or higher
- Composer (PHP package manager)
- MongoDB 4.4 or higher (optional - app works without it)

## Installation

1. Clone the repository:
```bash
cd /Users/janrosen/Dev/mvp-2/real-estate
```

2. Install PHP dependencies:
```bash
composer install
```

3. Set up environment variables:
```bash
cp .env.example .env
```

Edit `.env` to configure your MongoDB connection if needed. Default settings work with local MongoDB.

4. Configure storage mode (optional):
```bash
# Edit .env file to choose storage mode:
# STORAGE_MODE=session  (default - stores in browser session)
# STORAGE_MODE=mongodb  (requires MongoDB extension)
```

If using MongoDB mode, ensure MongoDB is running:
```bash
# On macOS with Homebrew
brew services start mongodb-community

# Or start manually
mongod
```

## Running the Application

Start the PHP development server:

```bash
composer start
```

Or manually:

```bash
php -S localhost:8000 -t public
```

Visit http://localhost:8000 in your browser.

## Project Structure

```
real-estate/
├── src/
│   ├── Controllers/     # Request handlers
│   ├── Models/          # MongoDB models
│   └── Views/           # PHP templates
├── public/              # Web root
│   ├── index.php       # Entry point
│   └── css/            # Stylesheets
├── data/               # Mock data
├── config/             # Configuration
└── composer.json       # Dependencies
```

## MongoDB Collections

- `listings` - Property listings (auto-populated with mock data)
- `saved_listings` - User's saved properties
- `preapproval_documents` - Mortgage pre-approval records
- `offers` - Property offers submitted by users

## Mock Data

The application automatically loads 10 sample property listings on first run. These include various homes in the Austin, TX area with different price points and features.

## User Sessions

The application uses PHP sessions to track users. Each visitor gets a unique session ID that persists their saved listings, pre-approvals, and offers.

## Features Overview

### Browse Listings
- Filter by city, price range, and minimum bedrooms
- View property photos, details, and descriptions
- Save properties for later

### Mortgage Pre-Approval
- Fill out financial information form
- Get instant pre-approval amount
- See estimated monthly payments and interest rates
- Pre-approval valid for 90 days

### Make Offers
- Submit offers on any property
- Include contingencies (inspection, appraisal, financing)
- Specify down payment percentage and closing date
- Track all submitted offers

## Development

To modify the application:

1. Models are in `src/Models/` - handle MongoDB operations
2. Controllers in `src/Controllers/` - handle HTTP requests
3. Views in `src/Views/` - PHP templates for rendering HTML
4. Styles in `public/css/style.css`

## Storage Modes

The application supports two storage modes:

### Session Storage Mode (Default)
- Data stored in PHP session (browser-based)
- No MongoDB required
- Data persists during browser session
- Resets when browser is closed

### MongoDB Mode
- Requires MongoDB PHP extension (`pecl install mongodb`)
- Data stored in MongoDB database
- Data persists permanently
- Can be shared across sessions

To switch modes, edit `STORAGE_MODE` in your `.env` file.

## Notes

- This is a demo application with simulated mortgage calculations
- No real financial data is processed
- Data storage depends on configured mode
- No external APIs are used (runs completely offline)