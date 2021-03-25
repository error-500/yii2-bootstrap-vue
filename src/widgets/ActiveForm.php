<?php

namespace yii\bootsrap_vue\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\ActiveForm as BaseActiveForm;
use yii\bootstrap_vue\widgets\VueField;
use yii\bootstrap_vue\assets\BootstrapVueAsset;

class ActiveForm extends BaseActiveForm
{

    public $fieldClass = 'yii\bootstrap_vue\widgets\VueField';
    // public $enableClientScript = false;
    public function registerClientScript()
    {
        $id         = $this->options['id'];
        $options    = Json::encode($this->getClientOptions());
        $attributes = Json::encode($this->attributes);
        $view       = $this->getView();
        Yii::$app->vueApp->data = [
            'form' => [
                'options'    => $options,
                'attributes' => $attributes,
            ],
        ];
        return $this;
    } //end registerClientScript()

    public function init()
    {
        BootstrapVueAsset::register($this->getView());
        return parent::init();
    }
    public function run()
    {
        $content = \ob_get_clean();

        $form  = Html::beginTag(
            'b-form',
            ArrayHelper::merge(
                ['method' => $this->method],
                $this->options
            )
        );
        $form .= "\n\t" . $content . "\n";
        $form .= Html::endTag('b-form');

        if ($this->enableClientScript) {
            $this->registerClientScript();
        }

        return $form;
    } //end run()


}//end class
