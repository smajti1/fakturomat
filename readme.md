# Fakturomat

Fakturomat is a system to create and manage your invoices

## require

[laravel](https://laravel.com/docs/5.4) 5.4

[php](http://www.php.net/) php7+

[php Internationalization Functions  extension](http://php.net/manual/en/book.intl.php) php7.0-intl

[wkhtmltopdf](http://wkhtmltopdf.org) recommended v0.12.3.^ version

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