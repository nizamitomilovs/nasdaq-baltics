### Simple web application which shows nasdaq stock prices by dates

#### Application is available only via api, no FE implemented

For base was used Laravel-Vue framework.<br>

## Setup
- >cp .env.example .env

Change database credentials in new .env file
- >composer install
- >php artisan key:generate
- >npm i && npm run dev
- >php artisan migrate


To start application use
- >php artisan serve

#### Running tests
- > vendor/bin/phpunit


## Command usage

There is one command which can be used from the terminal. Command is responsible for pulling and saving in database stock prices for specific date.<br>

- >php artisan download:stocks 2022-01-02
  > 
Where date is optional parameter, if no date is specified, command will use current date.
