<?php

/**
 * Milkyway Multimedia
 * FormFieldBootstrapper.php
 *
 * @package milkyway-multimedia/ss-zen-forms
 * @author  Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

use Milkyway\SS\ZenForms\Model\AbstractFormFieldDecorator;

class FormFieldBootstrapper extends AbstractFormFieldDecorator
{
	public $templateSuffix = '_bootstrapped';

	protected $holderAttributes = [];
	protected $holderClasses = [
		'form-group',
		'bootstrapped-field-holder',
	];

	protected $labelAttributes = [];
	protected $labelClasses = [
		'control-label',
	];

	protected $optionAttributes = [];
	protected $optionClasses = [];

	public function __construct($original)
	{
		parent::__construct($original);
		$this->addExtraClassesAndAttributes();
	}

	public function hideIf($field, $state = 'blank') {
		$att = $this->getHolderAttribute('data-hide-if');
		$this->setHolderAttribute('data-hide-if', trim($att . ',' . $this->getTargetFieldCondition($field, $state), ', '));

		return $this;
	}

	public function showIf($field, $state = 'blank') {
		$att = $this->getHolderAttribute('data-show-if');
		$this->setHolderAttribute('data-show-if', trim($att . ',' . $this->getTargetFieldCondition($field, $state), ', '));

		return $this;
	}

	public function addExtraClassesAndAttributes()
	{
		$field = $this->original();

		if ($this->isButton($field)) {
			$this->addExtraClass('btn');

			if (!$this->getAttribute('data-loading-text'))
				$this->setAttribute('data-loading-text', _t('LOADING...', 'Loading...'));
		}
        elseif (!$this->isNonFormControl($field)) {
			$this->addExtraClass('form-control');
		}

		if($this->isCheckbox($field)) {
			$field->addExtraClass('checkbox');
		}
	}

	public function getFieldHolderTemplates()
	{
		return $this->suffixTemplates($this->up()->getFieldHolderTemplates());
	}

	public function getSmallFieldHolderTemplates()
	{
		return $this->suffixTemplates($this->up()->getSmallFieldHolderTemplates());
	}

	public function getTemplates()
	{
		return $this->suffixTemplates($this->up()->getTemplates());
	}

	public function setHolderAttribute($attribute, $value)
	{
		$this->holderAttributes[$attribute] = $value;

		return $this;
	}

	public function getHolderAttribute($attribute)
	{
		$attributes = $this->getHolderAttributes();
		return isset($attributes[$attribute]) ? $attributes[$attribute] : null;
	}

	public function removeHolderAttribute($attribute)
	{
		if(isset($this->holderAttributes[$attribute]))
			unset($this->holderAttributes[$attribute]);

		return $this;
	}

	public function addHolderClass($class) {
		$this->holderClasses = array_merge($this->holderClasses, is_array($class) ? $class : explode(' ', $class));
		return $this;
	}

	public function removeHolderClass($class) {
		if(!is_array($class))
			$class = [$class];

		$this->holderClasses = array_diff($this->holderClasses, $class);

		return $this;
	}

	public function getHolderAttributes() {
		$attributes = $this->holderAttributes;
		$attributes['class'] = isset($attributes['class']) ? $attributes['class'] . implode(' ', $this->holderClasses) : implode(' ', $this->holderClasses);
		$attributes['class'] = $attributes['class'] . ' ' . $this->original()->Type() . '-holder';
		return $attributes;
	}

	public function HolderAttributesHTML()
	{
		return FormBootstrapper::get_attributes_for_tag($this->getHolderAttributes(), ['id']);
	}

	public function setLabelAttribute($attribute, $value)
	{
		$this->labelAttributes[$attribute] = $value;

		return $this;
	}

	public function removeLabelAttribute($attribute)
	{
		if(isset($this->labelAttributes[$attribute]))
			unset($this->labelAttributes[$attribute]);

		return $this;
	}

	public function addLabelClass($class) {
		$this->labelClasses = array_merge($this->labelClasses, is_array($class) ? $class : explode(' ', $class));
		return $this;
	}

	public function removeLabelClass($class) {
		if(!is_array($class))
			$class = [$class];

		$this->labelClasses = array_diff($this->labelClasses, $class);

		return $this;
	}

	public function LabelAttributesHTML()
	{
		$attributes = $this->labelAttributes;
		$attributes['class'] = isset($attributes['class']) ? $attributes['class'] . implode(' ', $this->labelClasses) : implode(' ', $this->labelClasses);

		return FormBootstrapper::get_attributes_for_tag($attributes, ['id', 'for']);
	}

	public function setOptionAttribute($attribute, $value)
	{
		$this->optionAttributes[$attribute] = $value;

		return $this;
	}

	public function removeOptionAttribute($attribute)
	{
		if(isset($this->optionAttributes[$attribute]))
			unset($this->optionAttributes[$attribute]);

		return $this;
	}

	public function addOptionClass($class) {
		$this->optionClasses = array_merge($this->optionClasses, is_array($class) ? $class : explode(' ', $class));
		return $this;
	}

	public function removeOptionClass($class) {
		if(!is_array($class))
			$class = [$class];

		$this->optionClasses = array_diff($this->optionClasses, $class);

		return $this;
	}

	public function OptionAttributesHTML()
	{
		$attributes = $this->optionAttributes;
		$attributes['class'] = isset($attributes['class']) ? $attributes['class'] . implode(' ', $this->optionClasses) : implode(' ', $this->optionClasses);

		if($this->original()->getAttribute('required'))
			$attributes['required'] = $this->up()->getAttribute('required');

		return FormBootstrapper::get_attributes_for_tag($attributes, ['disabled', 'checked', 'selected', 'value', 'type', 'name', 'id']);
	}

	protected function suffixTemplates(array $templates)
	{
		$new = [];

		foreach ($templates as $template) {
			$new[] = $template . $this->templateSuffix;
		}

		return array_unique(array_merge($new, $templates));
	}

	public function collateDataFields(&$list, $saveableOnly = false)
	{
		return $this->original()->collateDataFields($list, $saveableOnly);
	}

	public function __toString()
	{
		return is_object($this->forTemplate()) ? $this->forTemplate()->Value : $this->FieldHolder();
	}

	protected function getTargetFieldCondition($field, $state) {
        FormBootstrapper::requirements();

		if($field instanceof FormField) {
			$prefix = strpos($state, '[') === 0 ? '' : ':';

			if($field->Form)
				$condition = '#' . $field->ID() . $prefix . $state;
			else
				$condition = "[name='" . $field->ID() . "']" . $prefix . $state;
		}
		else
			$condition = $field . ':' . $state;

		return $condition;
	}

    public function isButton($field = null) {
        if(!$field) $field = $this->original();
        return $field instanceof FormAction || $field instanceof FormActionLink || $field instanceof FormActionNoValidation;
    }

    public function isNonFormControl($field = null) {
        if(!$field) $field = $this->original();
        return $field instanceof LiteralField || $field instanceof HeaderField || $field instanceof CompositeField || $field instanceof OptionsetField;
    }

    public function isCheckbox($field = null) {
        if(!$field) $field = $this->original();
        return $field instanceof CheckboxSetField;
    }
} 