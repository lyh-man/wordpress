# Linux（Centos）

+ 更换 yum 源
```text
ls -lh /etc/yum.repos.d
sudo cat /etc/yum.repos.d/CentOS-Base.repo
sudo mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo.backup
sudo wget -O /etc/yum.repos.d/CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-7.repo
 
sudo yum clean all
sudo yum makecache
```

+ 防火墙
```text
sudo systemctl status firewalld
sudo firewall-cmd --zone=public --list-ports
sudo firewall-cmd --add-port=80/tcp --zone=public --permanent
sudo firewall-cmd --add-port=3306/tcp --zone=public --permanent
sudo firewall-cmd --reload
```

### docker
+ [Install Docker Engine on CentOS](https://docs.docker.com/engine/install/centos/)
+ uninstall docker
```text
rpm -qa | grep docker

sudo yum remove -y docker docker-client docker-client-latest docker-common docker-latest docker-latest-logrotate docker-logrotate docker-engine
```

+ install docker
```text
sudo yum install -y yum-utils
sudo yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
sudo yum install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

sudo systemctl list-unit-files | grep docker
sudo systemctl enable docker

sudo systemctl start docker

docker --version
```

+ 更改 docker 镜像源
```text
vi /etc/docker/daemon.json

{
  "registry-mirrors": ["https://docker.mirrors.ustc.edu.cn"]
}

sudo systemctl daemon-reload
sudo systemctl restart docker
```

+ install docker-compose
```text
curl -L "https://github.com/docker/compose/releases/download/v2.27.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
ls -lh /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
docker-compose -v
```

### wordpress (docker compose)
+ [Compose and WordPress](https://github.com/docker/awesome-compose/blob/master/official-documentation-samples/wordpress/README.md)

+ install wordpress
```text
docker pull wordpress:6.5.3
docker pull mysql:8.4.0
```

+ docker-compose.yml
```text
version: '3.8'
services:
  mysql:
    image: mysql:8.4.0
    container_name: mysql
    restart: always
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: 'wordpress'
      MYSQL_DATABASE: 'wordpress'
      MYSQL_USER: 'wordpress'
      MYSQL_PASSWORD: 'wordpress'
    volumes:
      - db_data:/var/lib/mysql
  wordpress:
    depends_on:
      - mysql
    image: wordpress:6.5.3
    container_name: wordpress
    restart: always
    ports:
      - "80:80"
    environment:
      WORDPRESS_DB_HOST: mysql:3306
      WORDPRESS_DB_NAME: wordpress
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
    volumes:
      - wp_data:/var/www/html
volumes:
  db_data: {}
  wp_data: {}
```

+ start
```text
docker-compose up -d

docker-compose down

docker-compose ps
docker-compose logs -f    
```

+ 查看 volume
```text
docker volume ls
docker volume inspect wordpress_db_data
docker volume rm wordpress_db_data

ls -lh /var/lib/docker/volumes/
ls -lh /var/lib/docker/volumes/wordpress_db_data/_data/
ls -lh /var/lib/docker/volumes/wordpress_wp_data/_data/
```

### AI 大模型
+ 百度千帆大模型
```text
apikey
    b8047ab5-8d8e-4e91-b541-f8564d72d408
apitoken
    bce-v3/ALTAK-q9B9IrqqAtodXSNoofgkk/e5496860749c31f093166f8e8f3a26ecde405807
```