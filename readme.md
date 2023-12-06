# Fakturomat

Fakturomat is a system to create and manage your invoices

## Base project configuration steps

    git clone git@github.com:smajti1/fakturomat.git
    
    cd fakturomat
    
    cp .env.example .env
    
next set up .env file
    
    nano .env

build container with current user id (**optional** default id is `1000`)

    docker compose build --build-arg UID="$(id -u)"

#### Start server
This project use [caddy](https://caddyserver.com/docs) as a server and reverse proxy

You may need to create external docker network

    docker network create caddy

To work with domain use [caddy reverse_proxy](https://github.com/lucaslorentz/caddy-docker-proxy)
on development server should run on https://fakturomat.localhost next:

#### Start containers and install dependencies

    docker compose up --detach

get into docker container

    # you may need run attach.sh as root 
    ./attach.sh
    
    # optionaly if cannot create directories etc check uid (User ID)

    composer install --no-dev
    
    ./artisan key:generate

    ./artisan migrate

    npm run start

## Config

currency and taxes set in config/invoice.php

To see logs/debug use

    docker compose logs -f web_php

## Docs
- [development](docs/development.md)
- [postgres](docs/postgres.md)
- [vps start commands](docs/vps-start-commands.md)
- [docker config/setup/help list](docs/docker.md)
