# MediaShare
A Laravel and React site built for sharing images and videos.

## Installation

### Production
```
composer install
php artisan key:generate
php artisan migrate
php artisan passport:keys
npm install && npm run dev
```

### Development
```
composer install
php artisan key:generate
php artisan migrate
php artisan passport:install
npm install && npm run dev
``` 

### Testing
```
phpunit
npm run test
```
