Yii2 Bootstrap Vue
===================
Replace for yii2-bootstrap

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist error500/yii2-bootstrap-vue "*"
```

or add

```
"error500/yii2-bootstrap-vue": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

* Add vueApp component to config of your web application
```php
    componets => [
        ...
        'vueApp' => [
            'class' => 'yii\bootstrap_vue\VueObject',
        ],
        ...
    ]
```

```php
<?php $form = \yii\bootstrap_vue\widgets\ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?php echo $form->field($model, 'attribute', [])
                    ->text()
                    ->label();
            ?>
        </div>
    </div>
<?php \yii\bootstrap_vue\widgets\ActiveForm; ?>
```
