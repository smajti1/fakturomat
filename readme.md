# Fakturomat

Fakturomat is a system to create and manage your invoices

## environment require

[php](http://www.php.net/) php7+

[php Internationalization Functions  extension](http://php.net/manual/en/book.intl.php) php7.0-intl


all what [laravel](https://laravel.com/docs/5.4) 5.4 required

##### download and install wkhtmltopdf
[wkhtmltopdf](http://wkhtmltopdf.org) recommended v0.12.4.^ version

    $ sudo wget https://downloads.wkhtmltopdf.org/0.12/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz
    
    $ sudo unxz wkhtmltox-0.12.4_linux-generic-amd64.tar.xz
    
    $ sudo tar -xvf wkhtmltox-0.12.4_linux-generic-amd64.tar
    
    $ cd wkhtmltox/bin
    
    $ sudo mv wkhtmltopdf  /usr/bin/wkhtmltopdf
    
    $ sudo rm -rf wkhtmltox wkhtmltox-0.12.4_linux-generic-amd64.tar
    
## Download and set up project

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

test run
    
    $ php artisan serve

## Config

currency and taxes set in config/invoice.php