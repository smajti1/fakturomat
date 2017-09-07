# Fakturomat

Fakturomat is a system to create and manage your invoices

## environment require

[php](http://www.php.net/) php7+

[php Internationalization Functions  extension](http://php.net/manual/en/book.intl.php) php7.0-intl


and all what [laravel](https://laravel.com/docs/5.4) 5.4 required

[nodejs](https://nodejs.org) and [npm](https://www.npmjs.com/)

##### download and install wkhtmltopdf
[wkhtmltopdf](http://wkhtmltopdf.org) recommended v0.12.4.^ version

    $ sudo wget https://downloads.wkhtmltopdf.org/0.12/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz
    
    $ sudo unxz wkhtmltox-0.12.4_linux-generic-amd64.tar.xz
    
    $ sudo tar -xvf wkhtmltox-0.12.4_linux-generic-amd64.tar
    
    $ cd wkhtmltox/bin
    
    $ sudo mv wkhtmltopdf  /usr/bin/wkhtmltopdf
    
    $ sudo rm -rf wkhtmltox wkhtmltox-0.12.4_linux-generic-amd64.tar

##### to install frontend run command/s

    $ sudo apt install nodejs npm
    
## Download and set up project

    $ git clone git@bitbucket.org:smajti1/fakturomat.git
    
    $ cd fakturomat
    
    $ composer install
    
    $ npm run start
    
    $ cp .env.example .env
    
    $ php artisan key:generate
    
next set up .env file
    
    $ nano .env

next setup database

    $ php artisan migrate

test run server
    
    $ php artisan serve

## Config

currency and taxes set in config/invoice.php