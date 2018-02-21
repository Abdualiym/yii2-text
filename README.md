# yii2-slider extension

The extension allows build multi language slider.

## Installation

- Install with composer:

```bash
composer require abdualiym/yii2-slider
```

- **After composer install** run console command for create tables:

```bash
php yii migrate/up --migrationPath=@vendor/abdualiym/yii2-slider/migrations
```

- add to backend config file:
```php
'controllerMap' => [
    'slider' => [
        'class' => 'abdualiym\slider\controllers\SlideController',
        'attribute' => 'file',
        'filePath' => '@frontend/web/app-images/slider/[[attribute_id]]/[[id]].[[extension]]',
        'fileUrl' => '@frontendUrl/app-images/slider/[[attribute_id]]/[[id]].[[extension]]',
        'thumbPath' => '@frontend/web/app-temp/slider/cache/[[attribute_id]]/[[profile]]_[[id]].[[extension]]',
        'thumbUrl' => '@frontendUrl/app-temp/slider/cache/[[attribute_id]]/[[profile]]_[[id]].[[extension]]',
        'thumbs' => [
            'admin' => ['width' => 220, 'height' => 70],
            'thumb' => ['width' => 931, 'height' => 299],
        ],
    ],
],
```