### upgrade major version

    docker-compose exec db pg_dumpall -U USERNAME > pgdump
upgrade docker-compose.yml postgres version

    docker volume rm fakturomat_postgres
    docker-compose exec -T db psql -U USERNAME < pgdump
    
