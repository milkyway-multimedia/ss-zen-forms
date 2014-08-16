<?php

/**
 * Milkyway Multimedia
 * FormFieldBootstrapper.php
 *
 * @package milkyway-multimedia/mwm-zen-forms
 * @author  Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class FormFieldBootstrapper extends \Milkyway\SS\ZenForms\Model\AbstractFormFieldDecorator
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

	public function __construct($original)
	{
		parent::__construct($original);
		$this->addExtraClassesAndAttributes();
	}

	public function addExtraClassesAndAttributes()
	{
		$field = $this->original();

		if ($field instanceof FormAction
			|| $field instanceof FormActionLink
		) {
			$this->addExtraClass('btn');

			if (!$this->getAttribute('data-loading-text'))
				$this->setAttribute('data-loading-text', _t('LOADING...', 'Loading...'));
		} elseif (!($field instanceof LiteralField) && !($field instanceof HeaderField) && !($field instanceof CompositeField)) {
			$this->addExtraClass('form-control');
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

	public function HolderAttributesHTML()
	{
		$attributes = $this->holderAttributes;
		$attributes['class'] = isset($attributes['class']) ? $attributes['class'] . implode(' ', $this->holderClasses) : implode(' ', $this->holderClasses);
		$attributes['class'] = $attributes['class'] . ' ' . $this->original()->Type() . '-holder';

		return FormBootstrapper::get_attributes_for_tag($attributes, ['id']);
	}

	public function setLabelAttribute($attribute, $value)
	{
		$this->labelAttributes[$attribute] = $value;

		return $this->owner;
	}

	public function removeLabelAttribute($attribute)
	{
		if(isset($this->labelAttributes[$attribute]))
			unset($this->labelAttributes[$attribute]);

		return $this->owner;
	}

	public function addLabelClass($class) {
		$this->labelClasses = array_merge($this->holderClasses, is_array($class) ? $class : explode(' ', $class));
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
} 