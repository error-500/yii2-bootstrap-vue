<?php

namespace yii\bootstrap_vue\assets;

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

class VueAsset extends AssetBundle
{
    public $js = [
        [
            '//polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver',
            'position' => View::POS_HEAD,
            'crossorigin' => 'anonimus',
        ],
        [
            '//unpkg.com/vue@latest/dist/vue.js',
            'position' => View::POS_END,
        ],
    ];

    public $css = [
         [
            '//unpkg.com/vue@latest/dist/vue.js',
            'position' => View::POS_HEAD,
            'rel' => 'preload',
            'as' => 'script',
        ],
    ];
    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);

        $view->registerJs(
            'window.bundleUrl = "'.$this->baseUrl.'/";',
            \yii\web\View::POS_HEAD
        );
        if (!isset($view->js[\yii\web\View::POS_END]['vue-app-init'])) {
            Yii::$app->vueApp->mounted = [
                '$.holdReady(false);',
            ];
            $view->registerJs(
                "$.holdReady(true);\n".
                Yii::$app->vueApp,
                View::POS_END,
                'vue-app-init'
            );
        }

        // var_dump($view->js) & exit();

    }
}
