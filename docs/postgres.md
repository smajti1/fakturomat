### upgrade major version
In this step pay attention in:
- command `docker-compose up -d` may create postgres users/database
- pgdump file may have code to remove actual selected user/database

Dump database to file

    docker-compose exec db pg_dump --no-owner --username=USERNAME > pgdump.sql

upgrade postgres version in `docker-compose.yml` file

    docker-compose down
    docker volume rm fakturomat_postgres
    docker-compose up --detach
    # into next command may need to be to add --dbname=DBNAME
    docker-compose exec -T db psql --username=USERNAME < pgdump.sql
    

https://www.postgresql.org/docs/current/app-pgdump.html
