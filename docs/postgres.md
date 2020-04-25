### upgrade major version
In this step pay attention in:
- command `docker-compose up -d` may create postgres users/database
- pgdump file may have code to remove actual selected user/database


    docker-compose exec db pg_dumpall --username=USERNAME > pgdump
upgrade docker-compose.yml postgres version

    docker volume rm fakturomat_postgres
    docker-compose exec -T db psql --username=USERNAME < pgdump
    
