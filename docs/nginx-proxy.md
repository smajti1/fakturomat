### To start

    docker run -d -p 80:80 --network=gateway --restart=always -v /var/run/docker.sock:/tmp/docker.sock:ro nginxproxy/nginx-proxy

network should be the same that in docker-compose.yml

add new entry in `/etc/host` like:

    127.0.0.1 fakturomat.local

For more see https://github.com/nginx-proxy/nginx-proxy
