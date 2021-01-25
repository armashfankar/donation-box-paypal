# Technical Test - Donation Box using PayPal

Prepare a Laravel project & add payment gateway integration (Eg. Razorpay, CC Avenue, Stripe etc.). You can use the test mode for development. Prepare the donation box & perform end user level transactions.

Transaction: User will enter the amount say 100 & the transaction will deduct 3% from it & save into merchant's account. Follow ACID properties of databases


This is the codebase for the module, which has one interface:

### Demo Video
Link: https://res.cloudinary.com/armashfankar/video/upload/v1611608685/Donation-Paypal_iyajpw.mp4


## Getting started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

Here's a basic setup:

* Apache2
* PHP 7 - All the code has been tested against PHP 7.2
* Mysql (5.x), running locally
* Composer 2.0.8

* The module is written in the [Laravel 5.8](https://laravel.com/), and 
uses the [Blade](https://laravel.com/docs/8.x/blade) templating system.

 
### Installing

1. Clone the repository:
    ```shell script
    git clone https://github.com/armashfankar/donation-box-paypal.git

    ```

2. Install the requirements for the repository using the `composer`:
   ```shell script
    cd donation-box-paypal/
    composer install
    
    ```

3. Copy `.env.example` to create `.env` file:
    ```shell script
    cp .env.example .env
    
    ```

4. Configure Database & Cache Drive in `.env` file:
    
    1. Database
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=donation
    DB_USERNAME=root
    DB_PASSWORD=
    ```

    2. Paypal Credentials
    ```
    PAYPAL_CLIENT_ID=
    PAYPAL_SECRET=
    PANEL_PAYPAL_REDIRECT_URL=
    
    


5. Create MySQL Database:
     ```shell script
    mysql -u root -p

    create database donation;
    
    ```

6. Generate key for `.env` file:
    ```shell script
    php artisan key:generate
    
    ```

7. Migrate database:
    ```shell script
    php artisan migrate
    ```

8. Run / Execute:
    ```shell script
    php artisan serve
    
    ```

9. Open browser:
    ```
    http://localhost:8000
    ````
    
