Here’s a refined list of the Zillow (Bridge) APIs best suited for your PHP + MongoDB Rocket Mortgage demo, complete with input/output specs:

⸻

🏠 1. MLS Listings API

Purpose: Fetch real-time property listings.
	•	Endpoint: GET https://api.bridge.zillow.com/mls/listings
	•	Auth: Bridge Interactive credentials & access token via headers/query params  ￼
	•	Request Params (example):

mlsId=XYZMLS
status=Active
priceMin=300000
priceMax=600000
bedsMin=2
bathsMin=2
limit=20
offset=0


	•	Response (JSON):

{
  "listings":[
    {
      "listingId":"12345",
      "address":{"street":"123 Main St","city":"Austin","state":"TX","zip":"78701"},
      "price":450000,
      "beds":3,
      "baths":2,
      "sqft":1800,
      "photos":["https://..."],
      "description":"Charming home...",
      "lastUpdated":"2025-06-20T15:30:00Z"
    }
    ...
  ]
}



⸻

📊 2. Zestimate API

Purpose: Get official “Zestimate®” home valuations.
	•	Endpoint: via Bridge, e.g. GET /zestimates
	•	Auth: Bridge token  ￼
	•	Request Params:

zpid=12345678


	•	Response:

{
  "zestimate":350000,
  "rentZestimate":1800,
  "valueRange":{"low":330000,"high":370000}
}



⸻

📈 3. Get Current Rates API

Purpose: Fetch current mortgage rates and historical trends.
	•	Endpoint: GET https://mortgageapi.zillow.com/getRates  ￼
	•	Auth: partnerId=RD-EXAMPLE in headers or params
	•	Request Body (JSON POST):

{
  "partnerId":"RD-EXAMPLE",
  "queries":{
    "default": {
      "program":"Fixed30Year",
      "loanType":"Conventional",
      "stateAbbreviation":"US"
    }
  },
  "durationDays":30,
  "includeCurrentRate":true
}


	•	Response:

{
  "rates":{
    "default":{
      "query":{"program":"Fixed30Year",...},
      "samples":[{"time":"2025-06-22","rate":6.3,"apr":6.5}, ... ],
      "currentRate":{"time":"2025-06-23","rate":6.35,"apr":6.55}
    }
  }
}



⸻

📝 4. Lender Reviews API

Purpose: Fetch genuine reviews for lenders like Rocket Mortgage (NMLS ID: 3030).
	•	Endpoint: GET https://mortgageapi.zillow.com/zillowLenderReviews  ￼
	•	Auth: partnerId required
	•	Request Params:

lenderId=3030
limit=5


	•	Response:

{
  "lenderId":3030,
  "averageRating":4.5,
  "totalReviews":240,
  "reviews":[
    {"title":"Great service","text":"...","rating":5,"date":"2025-05-10"},
    ...
  ],
  "profileUrl":"https://www.zillow.com/lender/3030"
}



⸻

🎯 Recommended API Integration Flow
	1.	Home Search
	•	Query MLS Listings → display and save listingId
	2.	Property Detail
	•	Fetch live property info + Zestimate for each listing
	3.	Mortgage Rates & Calculator
	•	Fetch current rates (e.g., 30yr fixed) → integrate into payment calculator
	4.	Pre‑Approval / Offer UI (Custom in-PHP)
	•	Collect user data
	•	Show mock pre-approval & offer form (no API needed, use own Mongo)
	5.	Mortgage Credibility
	•	Show Rocket Mortgage lender rating & recent reviews

⸻

🔧 Architecture in PHP + MongoDB
	•	listings collection – store listingId + minimal metadata
	•	listings_saved_by_user – maintain userId, listingId
	•	Real-time API calls for live data; do not cache Zillow info
	•	Mongo for user-generated content: pre-approvals, saved; not Zillow data

⸻

Would you like me to draft sample PHP client code (cURL or Guzzle) for each of these endpoints next?