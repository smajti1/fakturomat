# Fakturomat

Fakturomat is a system to create and manage your invoices

## Download and base project configuration steps

    $ git clone git@bitbucket.org:smajti1/fakturomat.git
    
    $ cd fakturomat
    
    $ cp .env.example .env
    
next set up .env file
    
    $ nano .env

start containers

    $ docker-compose up --detach

get into docker container and install dependencies

    # you may need run attach.sh as root 
    $ ./attach.sh

    $ ./artisan key:generate

    $ ./artisan migrate
    
    $ composer install --no-dev

    $ npm run start

## Config

currency and taxes set in config/invoice.php

## Docs
- [postgres](docs/postgres.md)
