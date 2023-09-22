### add user if not exist
    passwd
    adduser hd
    adduser hd sudo
    apt install sudo


### add docker and www-data users next install docker and docker compose
    sudo groupadd docker
    sudo usermod -aG docker $USER
    sudo usermod -aG www-data $USER
    sudo usermod -aG hd www-data
[install docker](https://docs.docker.com/engine/install/debian)


### generate new ssh key and add it to github.com account
    ssh-keygen

