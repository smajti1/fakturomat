# Fakturomat

Fakturomat is a system to create and manage your invoices

## require

<a href="https://laravel.com/docs/5.3" alt="Laravel 5.3 docs">laravel 5.3</a>

php7+

<a href="http://php.net/manual/en/book.intl.php" alt="PHP: intl - Manual">php intl extension</a> php7.0-intl

<a href="http://wkhtmltopdf.org" alt="wkhtmltopdf official website">wkhtmltopdf</a> recommended v0.12.3.^ version

## installation

    $ git clone git@bitbucket.org:smajti1/fakturomat.git
    
    $ cd fakturomat
    
    $ composer install
    
    $ npm install
    
    $ bower install
    
    $ gulp
    
    $ cp .env.example .env
    
    $ php artisan key:generate
    
next set up .env file

    $ php artisan migrate
    
## config

currency and taxes set in config/invoice.php