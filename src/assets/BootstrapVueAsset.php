<?php

namespace yii\bootstrap_vue\assets;

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

class BootstrapVueAsset extends AssetBundle
{
    public $js = [
        [
            '//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js',
            'position' => View::POS_END,
        ],
        [
            '//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue-icons.js',
            'position' => View::POS_END,
        ],

    ];

    public $css = [

        [
            '//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js',
            'position' => View::POS_HEAD,
            'rel' => 'preload',
            'as' => 'script',
        ],
       [
            '//unpkg.com/bootstrap/dist/css/bootstrap.min.css',
            'position' => View::POS_HEAD,
            'rel' => 'stylesheet',
            'type' => 'text/css',
        ],
        [
            '//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css',
            'position' => View::POS_HEAD,
            'rel' => 'stylesheet',
            'type' => 'text/css',
        ]
    ];

    public $depends = [
        VueAsset::class,
    ];
}
