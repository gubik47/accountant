# Accountant backend

REST API backend for the Accountant app that tracks transactions of all your bank accounts uploaded via CSV files.

## Installation

`docker` and `docker-compose` need to be installed in order to run the app.

1. Create a docker network for **Traefik** reverse proxy: `docker network create traefik_proxy`
2. Create a docker network for the **Accountant** app: `docker network create accountant_network`
3. Create **Traefik** reverse proxy container and run it: `docker run -d -p 8080:8080 -p 80:80 -v /var/run/docker.sock:/var/run/docker.sock --name=traefik --network=traefik_proxy traefik:v2.6 traefik --api.insecure=true --providers.docker --providers.docker.exposedByDefault=false`
4. Add `127.0.0.1 mysql.accountant.local api.accountant.local` to your local DNS records, eg. `/etc/hosts`.
5. Start the application containers: `docker-compose up -d`
6. Start a bash inside the builder container `docker exec -it accountant_api_builder bash`
7. Run these commands inside the container:
   1. `composer install`
   2. `php bin/console d:d:c`
   3. `php bin/console d:s:u --force`
    
The API is now ready to be accessed at http://api.accountant.local/. PHPMyAdmin is available at http://mysql.accountant.local/ 

## API documentation
// TODO:
