## To get started 
```bash
$ composer install 
$ php bin/console assets:install 
$ cp .env .env.local  
$ put your local configuration entries in .env.local
$ bin/consele doctrine:database:create
$ bin/console doctrine:schema:update
```
## Configuration LexikJWTAuthentication
```bash
$ mkdir -p config/jwt   
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
## To run the server
```bash
$ bin/console symfony:server:start(don't forget to install symfony cli https://symfony.com/download) 
```
## License
[MIT](https://choosealicense.com/licenses/mit/)