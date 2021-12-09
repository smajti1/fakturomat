### To start

add new entry in `/etc/host` like:

    127.0.0.1 fakturomat.local

Next start **nginx-proxy**

    docker run --detach \
        --name nginx-proxy \
        --restart=always \
        --network=gateway \
        --publish 80:80 \
        --publish 443:443 \
        --volume /path/to/certs:/etc/nginx/certs \
        --volume vhost:/etc/nginx/vhost.d \
        --volume html:/usr/share/nginx/html \
        --volume /var/run/docker.sock:/tmp/docker.sock:ro \
        nginxproxy/nginx-proxy

network should be the same that in docker-compose.yml

For more see https://github.com/nginx-proxy/nginx-proxy

To use https and auto-renewal ssl certificate

    docker run --detach \
        --name nginx-proxy-acme \
        --restart=always \
        --network=gateway \
        --volumes-from nginx-proxy \
        --volume /var/run/docker.sock:/var/run/docker.sock:ro \
        --volume acme:/etc/acme.sh \
        --env "DEFAULT_EMAIL=mail@yourdomain.tld" \
        nginxproxy/acme-companion

To see logs/debug use
    
    docker logs -f nginx-proxy

For more see https://github.com/nginx-proxy/acme-companion
