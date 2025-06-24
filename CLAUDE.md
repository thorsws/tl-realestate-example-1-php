# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Status

This is a **not yet implemented** real estate and mortgage demo application. Currently, only the requirements document exists (`requirement.md`).

## Project Overview

A PHP + MongoDB web application that simulates a homebuyer journey:
- Browse property listings
- Save favorite properties  
- Get mortgage pre-approval
- Submit offers on properties

## Technology Stack

- **Language**: PHP (latest version)
- **Database**: MongoDB
- **Frontend**: Server-rendered HTML/CSS using PHP templates (no JavaScript frameworks)
- **External Data**: Mock or real property listing API

## MongoDB Collections

When implemented, the application will use these collections:
- `listings` - Property listings data
- `saved_listings` - User's saved properties
- `preapproval_documents` - Mortgage pre-approval data
- `offers` - Submitted property offers

## Implementation Notes

Since this project hasn't been started yet, when implementing:

1. **Initialize the PHP project** with composer and proper directory structure
2. **Set up MongoDB connection** using the MongoDB PHP driver
3. **Create modular code structure** with separate routes/controllers/views
4. **Organize by feature**: `/listings`, `/mortgage`, `/offers`
5. **Use server-side rendering** - no JavaScript frameworks required

## Development Commands

Once the project is initialized, typical commands will be:
- `composer install` - Install dependencies
- `php -S localhost:8000` - Run development server
- MongoDB should be running locally or configured with connection string

## Architecture Guidelines

- Keep all rendering server-side using PHP templates
- Use feature-based organization for routes and controllers
- Store all application data in MongoDB collections as specified
- If using mock data instead of API, create JSON fixtures in a `data/` directory