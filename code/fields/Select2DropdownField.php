<?php
/**
 * Milkyway Multimedia
 * Select2DropdownField.php
 *
 * @package
 * @author Mellisa Hankins <mellisa.hankins@me.com>
 */

class Select2DropdownField extends TypeAheadField {
	/**
	 * @var boolean $hasEmptyDefault Show the first <option> element as
	 * empty (not having a value), with an optional label defined through
	 * {@link $emptyString}. By default, the <select> element will be
	 * rendered with the first option from {@link $source} selected.
	 */
	protected $hasEmptyDefault = false;

	/**
	 * @var string $emptyString The title shown for an empty default selection,
	 * e.g. "Select...".
	 */
	protected $emptyString = '';

	protected $minSearchLength = 0;
	protected $prefetch = true;
	protected $suggestURL = false;

	public $allowHTML = true;
	public $disabledOptions = array();
	public $lockedOptions = array();

	public static function requirements() {
		if(Requirements::backend() instanceof MWMRequirements_Backend) {
			MWMForm::requirements();

			// MWMRequirements::combine(MWM_DIR . '/css/select2.min.css', 'forms');
			//MWMRequirements::combine(MWM_DIR . '/thirdparty/select2/select2.min.js', 'forms-select2');
			// MWMRequirements::combine(MWM_DIR . '/javascript/mwm.forms.select2.js', 'forms-select2');
		}
		else {
			// Requirements::javascript(MWM_DIR . '/thirdparty/select2/select2.min.js');
			// Requirements::javascript(MWM_DIR . '/javascript/mwm.forms.select2.admin.js');
		}
	}

	function Field($properties = array()) {
		$this->requirements();

		$this->addExtraClass('select2-drop has-chzn chzn-done');

		return TextField::Field($properties);
	}

	/**
	 * @param boolean $bool
	 */
	public function setHasEmptyDefault($bool) {
		$this->hasEmptyDefault = $bool;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getHasEmptyDefault() {
		return $this->hasEmptyDefault;
	}

	/**
	 * Set the default selection label, e.g. "select...".
	 * Defaults to an empty string. Automatically sets
	 * {@link $hasEmptyDefault} to true.
	 *
	 * @param string $str
	 */
	public function setEmptyString($str) {
		$this->setHasEmptyDefault(true);
		$this->emptyString = $str;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmptyString() {
		return $this->emptyString;
	}

	function getAttributes() {
		$vars = array(
			'data-suggest-url' => $this->SuggestURL,
			'data-prefetch-url' => $this->PrefetchURL,
			'data-minimum-input-length' => $this->minSearchLength,
			'data-require-selection' => $this->requireSelection,
			'data-allow-html' => $this->allowHTML,
		);

		$placeholder = $this->hasEmptyDefault ? $this->emptyString ? $this->emptyString : _t('Select2DropdownField.SELECT___', 'Select...') : ' ';

		if($placeholder) {
			$vars['placeholder'] = $placeholder;
			$vars['data-placeholder'] = $placeholder;
		}

		return array_merge(
			$vars, FormField::getAttributes(), array(
				'type' => 'hidden',
			)
		);
	}
}