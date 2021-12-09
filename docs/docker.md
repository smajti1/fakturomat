### check nginx logs
    docker-compose logs -f nginx

### if disk usage is too big see Storage Driver
    sudo docker info

If it's different from `overlay2` then check file system type

    df -hT

If `overlay2` is not available you can use `fuse-overlayfs` but first install `sudo apt install fuse-overlayfs`
more info in [documentation](https://docs.docker.com/storage/storagedriver/select-storage-driver)
