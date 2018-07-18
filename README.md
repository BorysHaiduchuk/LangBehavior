LangBehavior
============
Translatable behavior aggregates logic of linking translations to the primary model.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add composer.json
```
  "repositories": [
        {
            "type": "git",
            "url": "https://github.com/BorysHaiduchuk/LangBehavior.git"
        }

    ]
```


Either run

```
php composer.phar require --prefer-dist boryshaiduchuk/yii2-lang-behavior "*"
```

or add

```
"boryshaiduchuk/yii2-lang-behavior": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Add to ActiveRecord:
```php
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => LangBehavior::className(),
                't' => new PageLang(),
                'fk' => 'record_id',
                'l' => 1
            ],
        ];
    }```