### To start

add new entry in `/etc/hosts` like (ip may be different that in example see nginx-proxy see [Debug](#debug)):

    127.0.0.1 fakturomat.test
ensure host work

    getent ahosts fakturomat.test

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

## Debug

    docker exec nginx-proxy cat /etc/nginx/conf.d/default.conf

## Update

    docker pull nginxproxy/nginx-proxy
    docker stop nginxproxy/nginx-proxy
    docker rm nginxproxy/nginx-proxy

And start again
