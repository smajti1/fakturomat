# Fakturomat

Fakturomat is a system to create and manage your invoices

## Base project configuration steps

    git clone git@github.com:smajti1/fakturomat.git
    
    cd fakturomat
    
    cp .env.example .env
    
next set up .env file
    
    nano .env

Create the external network "gateway" (only once)

    docker network create gateway

start [nginx-proxy](docs/nginx-proxy.md)

start containers

    docker-compose up --detach

get into docker container and install dependencies

    # you may need run attach.sh as root 
    ./attach.sh
    
    # optionaly if cannot create directories etc check uid (User ID)

    composer install --no-dev
    
    ./artisan key:generate

    ./artisan migrate

    npm run start

## Config

currency and taxes set in config/invoice.php

## Docs
- [development](docs/development.md)
- [postgres](docs/postgres.md)
- [vps start commands](docs/vps-start-commands.md)
- [nginx reverse proxy](docs/nginx-proxy.md)
- [docker config/setup/help list](docs/docker.md)
