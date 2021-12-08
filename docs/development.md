### Development

Before start container uncomment `docker-compose.yml` service: `nginx` ports

Create and seed database tables
    
    ./artisan migrate:fresh
    ./artisan db:seed

#### phpstan
Before commit file run php analyse to find errors in code without running it

    ./artisan ide-helper:generate
    composer phpstan
read more on https://github.com/phpstan/phpstan
