Yii2 SEO module
==========

SEO-fields for your models: title, description, keyeords and some others

Install
---------------------------------

Run

```
php composer require pantera-digital/yii2-seo "*"
```

Or add to composer.json

```
"pantera-digital/yii2-seo": "*",
```

and execute:

```
php composer update
```

Миграция:

```
php yii migrate --migrationPath=vendor/pantera/yii2-seo/migrations
```

Usage
---------------------------------

Attach behavior to your model:

```php
    function behaviors()
    {
        return [
            'seo' => [
                'class' => 'pantera\seo\behaviors\SeoFields',
            ],
        ];
    }
```

Example of use in view:

```php

if (!$title = $model->seo->title) {
    $title = "Buy {$model->name} in store";
}

if (!$description = $model->seo->description) {
    $description = 'Page ' . $model->name;
}

if (!$keywords = $model->seo->keywords) {
    $keywords = '';
}

$this->title = $title;

$this->registerMetaTag([
    'name' => 'description',
    'content' => $description,
]);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $keywords,
]);

```

Widgets
---------------------------------

Add to your model form:
```
<?=\pantera\seo\widgets\SeoForm::widget([
        'model' => $model, 
        'form' => $form, 
    ]); ?>
```
