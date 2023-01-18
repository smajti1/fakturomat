### upgrade major version
In this step pay attention in:
- command `docker-compose up -d` may create postgres users/database
- pgdump file may have code to remove actual selected user/database

Dump database to file

DB_DATABASE and DB_USERNAME variable take from .env file 

    docker-compose exec db pg_dump --no-owner --dbname=DB_DATABASE --username=DB_USERNAME > pgdump.sql

upgrade postgres version in `docker-compose.yml` file

    docker-compose down
    docker volume rm fakturomat_postgres
    docker-compose up --detach
    docker-compose exec -T db psql --dbname=DB_DATABASE --username=USERNAME < pgdump.sql

Download pgdump.sql from server

    scp USERNAME@IP_ADDRESS:prod/fakturomat/pgdump.sql .

https://www.postgresql.org/docs/current/app-pgdump.html
