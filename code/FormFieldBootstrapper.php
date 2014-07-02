<?php /**
 * Milkyway Multimedia
 * FormFieldBootstrapper.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FormFieldBootstrapper extends \Milkyway\ZenForms\Model\BaseDecorator {
    public $templateSuffix = '_bootstrapped';

    protected $defaultHolderAttributes = array(
        'class' => 'form-group bootstrapped-field-holder field',
    );

    protected $holderAttributes = array();

    protected $defaultLabelAttributes = array(
        'class' => 'control-label',
    );

    protected $labelAttributes = array();

    public function __construct() {
        $args = func_get_args();

        call_user_func_array('parent::__construct', $args);
        array_shift($args);

        list($holderAttributes, $labelAttributes) = array_pad($args, -2, null);

        if($holderAttributes && count($holderAttributes))
            $this->holderAttributes = array_merge_recursive($this->defaultHolderAttributes, $holderAttributes);
        else
            $this->holderAttributes = $this->defaultHolderAttributes;

        if($labelAttributes && count($labelAttributes))
            $this->labelAttributes = array_merge_recursive($this->defaultLabelAttributes, $labelAttributes);
        else
            $this->labelAttributes = $this->defaultLabelAttributes;
    }

    public function apply() {
        $original = $this->original();

        $this->addHolderAttributes($original);
        $this->addLabelAttributes($original);
        $this->addExtraClassesAndAttributes($original);

        $this->applyFieldHolderTemplate($original);
        $this->applySmallFieldHolderTemplate($original);
        $this->applyTemplate($original);

        return $original;
    }

    public function addExtraClassesAndAttributes($field) {
        if($field instanceof FormAction
           || $field instanceof FormActionLink) {
            $field->addExtraClass('btn');

            if(!$field->getAttribute('data-loading-text'))
                $field->setAttribute('data-loading-text', _t('LOADING...', 'Loading...'));
        }
        elseif(!($field instanceof LiteralField) && !($field instanceof HeaderField) && !($field instanceof CompositeField)) {
            $field->addExtraClass('form-control');
        }
    }

    public function applyFieldHolderTemplate($field) {
        if($field->FieldHolderTemplate) return;
        $this->applyTemplates($field, 'FieldHolder');
    }

    public function applySmallFieldHolderTemplate($field) {
        if($field->SmallFieldHolderTemplate) return;
        $this->applyTemplates($field, 'SmallFieldHolder');
    }

    public function applyTemplate($field) {
        if($field->Template) return;
        $this->applyTemplates($field);
    }

    public function addHolderAttributes($field) {
        if(!$field->HolderAttributesHTML)
            $attributes = $this->holderAttributes;
        else
            $attributes = FormBootstrapper::get_attributes_from_tag($field->HolderAttributesHTML);

        $attributes['class'] = $attributes['class'] . ' ' . $field->Type() . '-holder';
        if($field instanceof CompositeField)
            $attributes['class'] = trim(str_replace(array('field', 'form-group'), '', $attributes['class']));
        $field->HolderAttributesHTML = FormBootstrapper::get_attributes_for_tag($attributes, array('id'));
    }

    public function addLabelAttributes($field) {
        if(!$field->LabelAttributesHTML)
            $field->LabelAttributesHTML = FormBootstrapper::get_attributes_for_tag($this->labelAttributes, array('id', 'for'));
    }

    public function remove() {
        $original = $this->original();
        return $original;
    }

    protected function applyTemplates($field, $type = '')
    {
        $getMethod = 'get' . $type . 'Templates';
        $setMethod = 'set' . $type . 'Template';

        $templates = $field->$getMethod();
        $new       = '';

        foreach ($templates as $template)
        {
            if (SSViewer::hasTemplate($template . $this->templateSuffix))
            {
                $new = $template . $this->templateSuffix;
                break;
            }
        }

        if ($new)
        {
            $field->$setMethod($new);
        }
    }
} 