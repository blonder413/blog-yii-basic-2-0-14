### Installation

rename ```config/db-example.php``` to ```config/db.php``` and use real data,
rename ```config/mailer-example.php``` to ```config/mailer.php``` and use real data,
rename ```config/params-example.php``` to ```config/params.php``` and use real data.

### Migration y Seeder

```php
./yii migrate
./yii seed
```

The seeds are in commands/SeedController, you can modifidy the index methos as you like

- imanilchaudhari\rrssb\ShareBar
- Growl alert (https://github.com/kartik-v/yii2-widget-alert)
```
composer require kartik-v/yii2-widget-growl "*"
```
- branchonline\lightbox\Lightbox (https://packagist.org/packages/branchonline/yii2-lightbox)
- kartik\file\FileInput;
```
http://demos.krajee.com/widget-details/fileinput
composer require kartik-v/bootstrap-fileinput "dev-master"
```
