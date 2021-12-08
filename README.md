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

Add VueAsset in view
```php
    VueAsset::register($this);
```

Use widget
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

Add bootstrap-vue http://bootstrap-vue.org/docs/components
in view file
```php
<?php
BootsrapVueAsset::register($this);
Yii::$app->vueApp->methods = [
    'fileChange' => 'function(file){
        ... some javascript code of root vue app object method ...
    }'
]
Yii::$app->vueApp->data = [
    'file' => null,
];
>
<b-container>
    <b-row>
...
        <b-form-file @change="fileChange" v-model="file"></b-form-file>
...
    </b-row>
<b-container>
```


Work with main Vue app in any place of your code
```php
Yii::$app->vueApp->data = [
    'prop1' => null,
    'prop2' => false,
    'prop3' => string,
]

Yii::$app->vueApp->methods = [
    'methodName' => 'function(args) {
        method javascript code
    }'
]
Yi::$app->vueApp->computed = [
    'computedPropName' => 'function(){ javascript }'
]
```
