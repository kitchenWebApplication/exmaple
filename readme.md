##Demo
`http://31.134.84.151`

**Manager account**
```
email: manager@kitchen.dev
pass: manager
``` 

**Waiter account**
```
email: waiter@kitchen.dev
pass: waiter
```

## Installation
Installation instruction.


### Install required operation system libraries
**laravel-echo-server**
```
npm install -g laravel-echo-server
```

### Install packages
```
composer install
npm install
```

### Configuration
* create a database
* copy **.env.example** to **.env** file and provide correct config values according to your environment
* run `php artisan key:generate`
* run `php artisan migrate --seed`
* run `php artisan queue:work --tries=5` as deamon
* copy **laravel-echo-server.json.example** to **laravel-echo-server.json** and provide correct config values according to your environment
* run `laravel-echo-server start` as deamon