# Fakturomat

Fakturomat is a system to create and manage your invoices

## environment require

[php](http://www.php.net/) php7.3+

[php Internationalization Functions  extension](http://php.net/manual/en/book.intl.php) php7.0-intl


and all what [laravel](https://laravel.com/docs/5.7) 5.7 required

[nodejs](https://nodejs.org) and [npm](https://www.npmjs.com/)

##### download and install wkhtmltopdf
[wkhtmltopdf](http://wkhtmltopdf.org) recommended v0.12.5-1.^ version

^tip check ubuntu version by command "lsb_release -a" for exmple ubuntu 18.04 has codename: bionic

    $ wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.5/wkhtmltox_0.12.5-1.bionic_amd64.deb
        
    $ sudo dpkg -i wkhtmltox_0.12.5-1.bionic_amd64.deb

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