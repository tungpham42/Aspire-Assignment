Aspire Assignment for Tung Pham
=

How to install Laravel 8
-

Firstly, you must check if your server meets the requirements:

    PHP >= 7.3

    BCMath PHP Extension

    Ctype PHP Extension

    Fileinfo PHP Extension

    JSON PHP Extension

    Mbstring PHP Extension

    OpenSSL PHP Extension

    PDO PHP Extension

    Tokenizer PHP Extension

    XML PHP Extension

There are many ways to install Laravel, but in this test I will use the easiest

    composer create-project laravel/laravel loan-app
    
    cd loan-app
    
If you don't have Composer installed, just go to https://getcomposer.org/download/ to get it.

Setup Database
-

Navigate to .env file, then edit

    DB_DATABASE=sample_db
    
    DB_USERNAME=sample_user
    
    DB_PASSWORD=sample_pass
    
with your desired credentials
