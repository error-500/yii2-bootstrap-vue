<?php

namespace yii\bootstrap_vue\widgets;

use Yii;
use yii\bootstrap_vue\assets\BootstrapVueAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveField as BaseActiveField;

class ActiveField extends BaseActiveField
{
    public $formGroupOptions = [];
    public $errorOptions  = ['class' => 'text-danger'];
    public $inputGroupOpt = ['size' => 'lg'];
    public $noInputGroup  = false;
    public $template      = "<b-input-group>\n
                        {label}
                        {input}\n
                        {hint}\n
                        </b-input-group>\n
                        {error}";


    public $parts = [
        '{label}' => null,
        '{input}' => null,
        '{hint}'  => null,
        '{error}' => null,
    ];

    public function init()
    {
        BootstrapVueAsset::register($this->getView());
        return parent::init();

    }
    public function begin()
    {
        $options = \array_key_exists('formGroupOptions', $this->options)
            && \is_array($this->options['formGroupOptions']) ? $this->options['formGroupOptions'] : [];
        $options = ArrayHelper::remove($this->options, 'formGroupOptions', []);
        if ($this->noInputGroup) {
            $options['label'] = $this->parts['{label}'];
        }
        if (empty($options['label-for'])) {
            $modelClass = explode('\\', $this->model->className());
            $domId = \strtolower(end($modelClass) . '-' . $this->attribute);
            $options['label-for'] =  $domId;
            $this->inputOptions['id'] = $domId;
        }
        if ($this->model->hasErrors($this->attribute)) {
            $options[':state.boolean'] = 'false';
        }
        return Html::beginTag('b-form-group', $options);
    } //end begin()


    public function render($content = null)
    {
        if ($content === null) {
            $attributeErrors = $this->model->getErrors($this->attribute);
            if (sizeof($attributeErrors)) {
                $this->parts['{error}'] = Yii::t(
                    'app',
                    "<b>Ошибка в \"{0}\"</b>: {1}",
                    [
                        $this->model->getAttributeLabel($this->attribute),
                        implode("\n", $attributeErrors),
                    ]
                );
                $inputOpts[':state.boolean']    = 'false';
            }
            if (!empty($this->parts['{label}'])) {
                if (!$this->noInputGroup) {
                    $label  = Html::beginTag('b-input-group-prepend', $this->labelOptions);
                    $label .= Html::tag('b-input-group-text', $this->parts['{label}'], ['class' => 'w-100']);
                    $label .= Html::endTag('b-input-group-prepend');
                    $this->parts['{label}'] = $label;
                }
            }

            if (!empty($this->parts['{hint}'])) {
                if ($this->noInputGroup) {
                    $hint = Html::tag('b-form-text', $this->parts['{hint}'], []);
                } else {
                    $hint  = Html::beginTag('b-input-group-append', []);
                    $hint .= Html::tag('b-input-group-text', $this->parts['{hint}']);
                    $hint .= Html::endTag('b-input-group-append');
                }

                $this->parts['{hint}'] = $hint;
            }


            if (!empty($this->parts['{error}'])) {
                $this->parts['{error}'] = Html::tag(
                    'b-form-group-invalid-feedback',
                    $this->parts['{error}'],
                    $this->errorOptions
                );
            }

            $content = \strtr($this->template, $this->parts);
        } else if (!is_string($content)) {
            $content = call_user_func($content, $this);
        } //end if

        return $this->begin() . "\n" . $content . "\n" . $this->end();
    } //end render()


    public function input($type, $options = [])
    {
        $inputOpts = ArrayHelper::merge(
            $this->inputOptions,
            [
                'type'  => $type,
                'name'  => $this->attribute,
                'value' => $this->model->{$this->attribute},
            ],
            $options
        );
        if ($this->form->enableClientScript) {
            $inputOpts['v-model']   = "form.{$this->attribute}";
            Yii::$app->vueApp->data = [
                'form' => [
                    $this->attribute => !is_string($inputOpts['value'])
                        ? $inputOpts['value'] : '"' . $inputOpts['value'] . '"',
                ],
            ];
        }


        $this->parts['{input}'] = Html::tag(
            'b-form-input',
            '',
            $inputOpts
        );

        return $this;
    } //end input()


    public function hiddenInput($options = [])
    {
        $this->parts['{input}'] = $this->input(
            'hidden',
            '',
            ArrayHelper::merge(
                [
                    'type'  => 'hidden',
                    'name'  => $this->attribute,
                    'value' => $this->model->{$this->attribute},
                ],
                $options
            )
        );
        return $this;
    } //end hiddenInput()


    public function passwordInput($options = [])
    {
        $this->parts['{input}'] = $this->input('password', '', $options);
        return $this;
    } //end passwordInput()


    public function fileInput($options = [])
    {
        $this->parts['{input}'] = Html::tag('cd-upload-field', '', $options);
        return $this;
    } //end fileInput()


    public function textarea($options = [])
    {
        $inputOpts = ArrayHelper::merge(
            $this->inputOptions,
            [
                'name'  => $this->attribute,
                'value' => '"' . $this->model->{$this->attribute} . '"',
            ],
            $options
        );
        if ($this->form->enableClientScript) {
            $inputOpts['v-model']   = "form.{$this->attribute}";
            Yii::$app->vueApp->data = [
                'form' => [
                    $this->attribute => $inputOpts['value'],
                ],
            ];
        }

        $this->parts['{input}'] = Html::tag(
            'b-form-textarea',
            $this->model->{$this->attribute},
            $inputOpts
        );

        return $this;
    } //end textarea()


    public function radio($options = [], $enclosedByLabel = true)
    {
        $label = \array_key_exists('label', $options) ? $options['label'] : '';
        ArrayHelper::remove($options, 'label');
        $this->parts['{input}'] = Html::tag('b-form-radio', $label, $options);
        return $this;
    } //end radio()

    public function radioList($items, $options = [])
    {
        array_walk(
            $items,
            function (&$label, $value) use ($options) {
                $options = ArrayHelper::merge(
                    $options,
                    [
                        'v-model' => $options['name'],
                        'value' => $value
                    ]
                );
                $label = Html::tag('b-form-radio', $label, $options);
            }
        );
        Yii::$app->vueApp->data = [
            'form' => [
                $options['name'] => $options['value']
            ]
        ];
        $this->parts['{input}'] = implode(
            "\n",
            $items
        );
    }

    public function checkbox($options = [], $enclosedByLabel = true)
    {
        $label = \array_key_exists('label', $options) ? $options['label'] : '';
        ArrayHelper::remove($options, 'label');
        $this->parts['{input}'] = Html::tag('b-form-checkbox', $label, $options);
        return $this;
    } //end checkbox()


    public function dropDownList($items, $options = [])
    {
        $options[':options']    = \is_string($items) ? $items : Json::encode($items);
        $this->parts['{input}'] = Html::tag('b-form-select', '', $options);
        return $this;
    } //end dropDownList()


    public function listBox($items, $options = [])
    {
        $this->parts['{input}'] = Html::tag('cd-modal-select', '', $options);
        return $this;
    } //end listBox()


    public function label($label = null, $options = [])
    {
        $this->parts['{label}'] = $label;
        $this->labelOptions     = ArrayHelper::merge($this->labelOptions, $options);
        return $this;
    } //end label()


    public function end()
    {
        return Html::endTag('b-form-group');
    } //end end()


}//end class