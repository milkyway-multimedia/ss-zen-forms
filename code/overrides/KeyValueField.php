<?php namespace Milkyway\SS\ZenForms\Overrides;

/**
 * Milkyway Multimedia
 * KeyValueField.php
 *
 * @package relatewell.org.au
 * @author  Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

if (class_exists('KeyValueField')) {
	class KeyValueField extends \KeyValueField
	{
		public $separator = ' ';

        public $keyFieldClass = 'TextField';
        public $valFieldClass = 'TextField';

        public $keyFieldClassForLists = 'Dropdown';
        public $valFieldClassForLists = 'Dropdown';

		public function getAttributes()
		{
			$this->addExtraClass('multivaluefieldlist mvkeyvallist');

			return parent::getAttributes();
		}

		public function Field($properties = []) {
			\Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
			\Requirements::javascript('multivaluefield/javascript/multivaluefield.js');
			\Requirements::css('multivaluefield/css/multivaluefield.css');

			return \FormField::Field($properties);
		}

		public function getChildren()
		{
			$fields = \ArrayList::create();
			$classes = 'mventryfield';

			if($this->readonly)
				$classes .= ' mvkeyvalReadonly';

			$keyField = $this->KeyField();
			$valField = $this->ValField();

			if ($this->value) {
				foreach ($this->value as $key => $val) {
					$fields->push(\ArrayData::create([
						'CssClasses' => $classes,
						'KeyField' => $this->transformField($keyField->castedCopy(get_class($keyField))->setValue($key)),
						'ValField' => $this->transformField($valField->castedCopy(get_class($valField))->setValue($val)),
					]));
				}
			}

			$fields->push(\ArrayData::create([
				'CssClasses' => $classes,
				'KeyField' => $this->transformField($keyField->castedCopy(get_class($keyField))),
				'ValField' => $this->transformField($valField->castedCopy(get_class($valField))),
			]));

			return $fields;
		}

		public function KeyField() {
			if($this->sourceKeys && count($this->sourceKeys))
				return \Object::create($this->keyFieldClassForLists, '[key][]', '', $this->sourceKeys)->addExtraClass('mventryfield mvdropdown');
			else
				return \Object::create($this->keyFieldClass, $this->name . '[key][]')->addExtraClass('mventryfield mvtextfield');
		}

		public function ValField() {
			if($this->sourceValues && count($this->sourceValues))
				return \Object::create($this->valFieldClassForLists, $this->name . '[val][]', '', $this->sourceValues)->addExtraClass('mventryfield mvdropdown');
			else
				return \Object::create($this->valFieldClass, $this->name . '[val][]')->addExtraClass('mventryfield mvtextfield');
		}

		public function Type() {
			return strpos(parent::Type(), strtolower(__NAMESPACE__ . '\\')) === 0 ? substr(parent::Type(), strlen(__NAMESPACE__ . '\\')) : parent::Type();
		}

		public function Separator() {
			return $this->separator;
		}

		public function setSeparator($separator) {
			$this->separator = $separator;
			return $this;
		}

		protected function transformField($field) {
			if($this->readonly) {
				return $field->performReadonlyTransformation();
			}
			elseif($this->disabled) {
				return $field->performDisabledTransformation();
			}

			return $field;
		}
	}
}