### Development

Before start container uncomment `docker-compose.yml` service: `nginx` ports

Local development work only on `http`

Create and seed database tables
    
    ./artisan migrate:fresh
    ./artisan db:seed
