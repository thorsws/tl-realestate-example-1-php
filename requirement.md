Here’s a clean, technical requirement document (with embedded prompt phrasing) you can give to Claude to generate the initial PHP + MongoDB real estate + mortgage demo app codebase.

⸻

📝 Project Requirements: Real Estate + Mortgage Demo App

🧩 Overview

Build a functional real estate and mortgage demo application using PHP and MongoDB. This app simulates a homebuyer journey from browsing property listings to receiving mortgage pre-approval and submitting an offer. It should resemble a simplified Rocket Mortgage-style user flow, tailored for both real estate and lending stakeholders.

⸻

🛠️ Tech Stack
	•	Language: PHP (latest version)
	•	Database: MongoDB
	•	Frontend: Server-rendered HTML/CSS using PHP templates (no JS frameworks needed)
	•	External Data: Use a mock or real property listing API (see below)

⸻

👥 Actors
	•	Buyer (User): Browses listings, saves favorites, submits mortgage info, sends offers.
	•	AI Assistant (Simulated): Not required for this version, but app should expose flows in a way that can be easily automated or driven later.
	•	Backend: Handles listings, saved state, mortgage logic, and document simulation.

⸻

📦 Features

1. Property Listings
	•	Pull property listings from a public API (e.g. Estated, Realtor.com) or simulate with local JSON files.
	•	Display key info: photo, address, price, beds, baths, description.
	•	Allow filter by city, price range, bedrooms.
	•	Allow users to “save” a listing (store in MongoDB by session/user).

2. Saved Listings
	•	List all saved properties for a given session/user.
	•	Allow removal.

3. Mortgage Pre-Approval
	•	Form to collect buyer inputs:
	•	Income
	•	Employment status
	•	Credit score (self-declared)
	•	Desired loan amount
	•	Simulate mortgage offer calculation:
	•	Choose from fixed rate types (e.g. 30yr fixed @ 6.25%)
	•	Store in MongoDB as preapproval_documents collection
	•	Display approval letter (HTML format, with downloadable PDF optional)

4. Make an Offer
	•	Select from saved listings.
	•	Fill out offer form:
	•	Offer price
	•	Contingencies
	•	Buyer info (name, email)
	•	Store in offers collection in MongoDB.
	•	Generate offer summary page.

⸻

🗂️ MongoDB Collections
	•	listings – Preloaded or API-fetched homes
	•	saved_listings – { user_id, listing_id }
	•	preapproval_documents – { user_id, income, credit_score, rate, approved_amount }
	•	offers – { user_id, listing_id, offer_price, contingencies, timestamp }

⸻

📡 Listing API Options

Use one of:
	•	Estated (https://estated.com/)
	•	Realtor.com (if accessible)
	•	Local JSON mock (if no API access — simulate 10–20 listings)

⸻

📐 Structure and Architecture
	•	Keep code modular: separate routes/controllers/views.
	•	Organize by feature (e.g. /listings, /mortgage, /offers).
	•	Avoid frontend JS or frameworks — this is a server-side rendered app.

⸻

✅ Deliverables
	•	Fully working PHP app with:
	•	Listings page
	•	Save/list saved homes
	•	Pre-approval flow
	•	Offer flow
	•	All data stored in MongoDB
	•	README with setup instructions and sample listing data if mocking API

⸻

Let me know if you want this translated into a full project scaffold or broken into task tickets for implementation.