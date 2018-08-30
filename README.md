Yii2 SEO module
==========

SEO-fields for your models: title, description, keywords and some others

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
php yii migrate --migrationPath=vendor/pantera-digital/yii2-seo/migrations
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

Нужно подключить компонент в конфиг

```
'components' => [
    'seo' => [
        'class' => pantera\seo\components\SeoComponent::className(),
    ],
]
```

### Not found
Для логирования нужно в обработчик ошибок добавить
```
if ($exception instanceof NotFoundHttpException) {
    $logger = new pantera\seo\models\SeoNotFound();
    $logger->url = Yii::$app->request->url;
    $logger->referrer = Yii::$app->request->referrer;
    $logger->ip = Yii::$app->request->getRemoteIP();
    $logger->save();
}
```

### Slug
В модель нужно добавить поведение
```
public function behaviors()
{
    return [
        [
            'class' => pantera\seo\behaviors\SlugBehavior::className(),
            'attribute' => 'title',
            'slugAttribute' => 'slug',
        ],
    ];
}
```
В модель подключить валидатор
```
public function rules()
{
    return [
        [['slug'], pantera\seo\validators\SlugValidator::className(), 'skipOnEmpty' => false],
    ];
}
```
Сконфигурировать свой UrlManager
