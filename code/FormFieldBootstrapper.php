<?php /**
 * Milkyway Multimedia
 * FormFieldBootstrapper.php
 *
 * @package milkyway-multimedia/mwm-zen-forms
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FormFieldBootstrapper extends \Milkyway\ZenForms\Model\AbstractFormFieldDecorator {
    public $templateSuffix = '_bootstrapped';

    protected $holderAttributes = array();
    protected $holderClasses = array(
        'form-group',
        'bootstrapped-field-holder',
    );

    protected $labelAttributes = array();
    protected $labelClasses = array(
        'control-label',
    );

    public function __construct($original) {
        parent::__construct($original);
        $this->addExtraClassesAndAttributes();
    }

    public function addExtraClassesAndAttributes() {
        $field = $this->original();

        if($field instanceof FormAction
           || $field instanceof FormActionLink) {
            $this->addExtraClass('btn');

            if(!$this->getAttribute('data-loading-text'))
                $this->setAttribute('data-loading-text', _t('LOADING...', 'Loading...'));
        }
        elseif(!($field instanceof LiteralField) && !($field instanceof HeaderField) && !($field instanceof CompositeField)) {
            $this->addExtraClass('form-control');
        }
    }

    public function getFieldHolderTemplates() {
        return $this->suffixTemplates($this->up()->getFieldHolderTemplates());
    }

    public function getSmallFieldHolderTemplates() {
        return $this->suffixTemplates($this->up()->getSmallFieldHolderTemplates());
    }

    public function getTemplates() {
        return $this->suffixTemplates($this->up()->getTemplates());
    }

    public function HolderAttributesHTML() {
        $attributes = $this->holderAttributes;
        $attributes['class'] = isset($attributes['class']) ? $attributes['class'] . implode(' ', $this->holderClasses) : implode(' ', $this->holderClasses);
        $attributes['class'] = $attributes['class'] . ' ' . $this->original()->Type() . '-holder';
        return FormBootstrapper::get_attributes_for_tag($attributes, array('id'));
    }

    public function LabelAttributesHTML() {
        $attributes = $this->labelAttributes;
        $attributes['class'] = isset($attributes['class']) ? $attributes['class'] . implode(' ', $this->labelClasses) : implode(' ', $this->labelClasses);
        return FormBootstrapper::get_attributes_for_tag($attributes, array('id', 'for'));
    }

    protected function suffixTemplates(array $templates)
    {
        $new = array();

        foreach($templates as $template) {
            $new[] = $template . $this->templateSuffix;
        }

        return array_unique(array_merge($new, $templates));
    }

    public function collateDataFields(&$list, $saveableOnly = false) {
        return $this->original()->collateDataFields($list, $saveableOnly);
    }

    public function __toString() {
        return is_object($this->forTemplate()) ? $this->forTemplate()->Value : $this->FieldHolder();
    }
} 