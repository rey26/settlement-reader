# Settlement reader

Reads data about cities and villages in Slovakia from website [e-obce.sk](https://www.e-obce.sk) and stores structured data in DB. The data can be viewed in admin view provided by EasyAdmin bundle and accessed by API in future releases.

## Installation

1. `docker-compose up -d --build` inside main directory

## Usage

### ImportSettlementsCommand

This command downloads data from external site, maps it to project entities and stores to DB. In order to also download coat of arms of each available city/village, run the command with option `coa-download`

```sh
docker-compose exec php /bin/bash
php bin/console app:import-settlements
# download coat of arms locally
php bin/console app:import-settlements --coa-download
```

## Testing

1. run `php bin/phpunit` inside php container (`docker-compose exec php /bin/bash`)
