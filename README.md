# Laravel Jetstream CRUD & Payment Integration

This project implements product management, Razorpay payment integration, bulk product import/export via Excel, and a product listing feature. It uses Laravel Jetstream with Inertia.js for building the user interface.

## Features

1. **Product Management (CRUD)**:

    - Create, Read, Update, Delete products.
    - Each product contains a name, price, and associated brand.

2. **Brand Management**:

    - Brands are related to products.
    - Each product is associated with a specific brand.

3. **Bulk Product Upload (Excel)**:

    - Users can upload a bulk product list via Excel using `maatwebsite/excel`.

4. **Excel Export**:

    - Users can export the list of products to an Excel file.

5. **Razorpay Payment Integration**:

    - Razorpay integration for creating payment orders.
    - Payment gateway integrated for direct purchasing from a product page.

6. **Single Product Page with Purchase Option**:

    - Display detailed product information.
    - "Buy Now" button that integrates Razorpay for purchasing.

7. **Product Listing on Home Screen**:
    - List all products available on the home screen with a user-friendly interface.

## Installation

Follow these steps to get your development environment set up.

### Prerequisites

-   PHP >= 8.2
-   Composer
-   Laravel 10
-   Node.js and npm
-   MySQL or any relational database system
