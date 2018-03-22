# api-zend
An API whit Zend Expressive and GraphQL

## Docker Containers
### Php7
```
    docker run -it -v /var/www:/var/www -p 80:80 --name php alpine
```
```
    apk update && apk upgrade && apk add php7 php7-fpm php7-opcache
```
### Composer
 ```
    docker run -it -v /var/www:/var/www --name composer alpine
```
```
    apk add --update git curl openssl php7 php7-json php7-phar php7-iconv php7-openssl php7-zlib && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer
```
```
    composer require zendframework/zendframework
```
    
### nginx
 ```
    docker run -it -v /var/www:/var/www --name nginx alpine
```
```
    apk update && apk upgrade && apk add nginx
```
    
### Limpando containers
    apk del curl openssl
	  rm -rf /var/cache/apk/*
