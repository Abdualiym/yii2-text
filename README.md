# yii2-slider extension

The extension allows build multi language slider.

## Installation

- Install with composer:

```bash
composer require abdualiym/yii2-text
```

- **After composer install** run console command for create tables:

```bash
php yii migrate/up --migrationPath=@vendor/abdualiym/yii2-text/migrations
```

- add to backend config file:
```php
'controllerMap' => [
    'categories' => [
        'class' => 'abdualiym\text\controllers\CategoryController',
    ],
    'text' => [
        'class' => 'abdualiym\text\controllers\TextController',
    ],
],
```
