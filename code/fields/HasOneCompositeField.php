<?php
/**
 * Milkyway Multimedia
 * HasOneCompositeField.php
 *
 * A compositefield that saves the containing fields
 * into a has_one relationship
 *
 * @todo No deletion of object supported...
 * @todo Saving has_many and many_many not tested...
 *
 * @package milkyway/silverstripe-hasonecompositefield
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

class HasOneCompositeField extends CompositeField {
	/**
	 * @var DataObjectInterface
	 */
	protected $record;

	/**
	 * @var array
	 */
	protected $extraData = array();

	/**
	 * @var array
	 */
	protected $defaultFromParent = array();

	/**
	 * @var array
	 */
	protected $overrideFromParent = array();

	/**
	 * @var bool
	 */
	protected $allowEmpty = false;

	private $prependedFields;
	private $originalFields;

	public function __construct($name, $record = null, FieldList $fields = null) {
		$this->name = $name;
        $this->record = $record;

		if(!$fields && $this->record)
            $fields = $this->fieldsFromRecord($this->record);

        if(!$fields)
            $fields = FieldList::create();

		parent::__construct($fields);
	}

	public function setRecord($record) {
		$this->record = $record;
		return $this;
	}

	public function getRecord() {
		return $this->record;
	}

    protected function recordFromForm() {
        if($this->Form && $this->Form->Record) {
            $relName = substr($this->name, -2) == 'ID' ? substr($this->name, -2, 2) : $this->name;

            if($this->Form->Record->hasMethod($relName))
                return $this->Form->Record->$relName();
        }

        return null;
    }

    protected function fieldsFromRecord($record) {
        if($record->hasMethod('getHasOneCMSFields'))
            $fields = $record->getHasOneCMSFields();
        else
            $fields = $record->getCMSFields();

        if($fields)
            return $fields;

        return FieldList::create();
    }

	public function setExtraData($data = array()) {
		$this->extraData = $data;
		return $this;
	}

	public function getExtraData() {
		return $this->extraData;
	}

	public function setDefaultFromParent($data = array()) {
		$this->defaultFromParent = $data;
		return $this;
	}

	public function getDefaultFromParent() {
		return $this->defaultFromParent;
	}

	public function setOverrideFromParent($data = array()) {
		$this->overrideFromParent = $data;
		return $this;
	}

	public function getOverrideFromParent() {
		return $this->overrideFromParent;
	}

	public function allowEmpty($flag = true) {
		$this->allowEmpty = $flag;
		return $this;
	}

	public function hasData() {
		return true;
	}

	/*public function isComposite() {
		return false;
	}*/

	public function collateDataFields(&$list, $saveableOnly = false) {
		// $list[$this->name] = $this;

		if(!$saveableOnly) {
			$fields = $this->FieldList(true);

			foreach($fields as $field) {
				if(is_object($field)) {
					if($field->isComposite()) $field->collateDataFields($list, $saveableOnly);
					if($saveableOnly) {
						$isIncluded =  ($field->hasData() && !$field->isReadonly() && !$field->isDisabled());
					} else {
						$isIncluded =  ($field->hasData());
					}
					if($isIncluded) {
						$name = $field->getName();
						if($name) {
							$formName = (isset($this->form)) ? $this->form->FormName() : '(unknown form)';
							if(isset($list[$name])) {
								user_error("collateDataFields() I noticed that a field called '$name' appears twice in"
									. " your form: '{$formName}'.  One is a '{$field->class}' and the other is a"
									. " '{$list[$name]->class}'", E_USER_ERROR);
							}
							$list[$name] = $field;
						}
					}
				}
			}
		}
	}

	public function saveInto(DataObjectInterface $parent) {
		$record = $this->record;

		$parent->flushCache(false); // Flush session cache in case a relation id was changed during form->saveInto

		if(!$record) {
			$relName = substr($this->name, -2) == 'ID' ? substr($this->name, -2, 2) : $this->name;

			if($parent->hasMethod($relName))
				$record = $parent->$relName();
		}

		$fields = $this->FieldList(false);

		if($record) {
			// HACK: Use a fake Form object to save data into fields
			$form = new Form(singleton('Controller'), $this->name . '-form', $fields, new FieldList());
			$form->loadDataFrom($this->value);

			if(!$this->allowEmpty && !$record->exists() && !count($form->Data))
				return;

			$form->saveInto($record);
			unset($form);

			// Save extra data into field
			if(count($this->extraData))
				$record->castedUpdate($this->extraData);

			if(!$record->exists() && count($this->defaultFromParent)) {
				foreach($this->defaultFromParent as $pField => $rField) {
					if(is_numeric($pField)) {
						if($record->$rField) continue;
						$record->setCastedField($rField, $parent->$rField);
					}
					else {
						if($record->$pField) continue;
						$record->setCastedField($rField, $parent->$pField);
					}
				}
			}

			if(count($this->overrideFromParent)) {
				foreach($this->overrideFromParent as $pField => $rField) {
					if(is_numeric($pField))
						$record->setCastedField($rField, $parent->$rField);
					else
						$record->setCastedField($rField, $parent->$pField);
				}
			}

			$record->write();

			$fieldName = substr($this->name, -2) == 'ID' ? $this->name : $this->name . 'ID';
			$parent->$fieldName = $record->ID;
		}
		elseif($parent) {
			$form = new Form(singleton('Controller'), $this->name . '-form', $fields, new FieldList());
			$form->loadDataFrom($this->value);
			$data = $form->Data;
			unset($form);

			if(count($this->extraData))
				$data += $this->extraData;

			$field = $this->name;
			$parent->$field = $data;
		}
	}

	public function FieldList($prependName = true) {
		$fields = parent::FieldList();

        if((!$fields || !$fields->exists()) && $record = $this->recordFromForm())
            $this->children = $fields = $this->fieldsFromRecord($record);

		if($fields && $fields->exists()) {
			if(!$this->originalFields)
				$this->originalFields = clone $fields;

			if($this->value && (is_array($this->value) || ($this->value instanceof DataObjectInterface)))
				$value = $this->value;
			else
				$value = $this->record;

			if(!$value && $this->form && $this->form->Record) {
				$relName = substr($this->name, -2) == 'ID' ? substr($this->name, -2, 2) : $this->name;

				if($this->form->Record->hasMethod($relName))
					$value = $this->form->Record->$relName();
			}

			if($value) {
				// HACK: Use a fake Form object to save data into fields
				$this->unprependName($fields);

				$form = new Form(singleton('Controller'), $this->name . '-form', $fields, new FieldList());

				$form->loadDataFrom($value);
				$fields->setForm($this->form);
				unset($form);
			}

			if($prependName)
				$this->prependName($fields);
			elseif(!$value)
				$this->unprependName($fields);
		}

		return $fields;
	}

	protected function prependName(FieldList $fields) {
		foreach($fields as $field){
			if($field->PrependedName) continue;

			if($field->isComposite())
				$this->prependName($field->FieldList());

			if(strpos($field->Name, $this->name . '[') !== 0)
				$field->setName($this->name . '[' . $field->Name . ']');

			$field->PrependedName = true;
			$field->UnPrependedName = false;
		}
	}

	protected function unprependName(FieldList $fields) {
		foreach($fields as $field){
			if($field->UnPrependedName) continue;

			if($field->isComposite())
				$this->unprependName($field->FieldList());

			if(strpos($field->Name, $this->name . '[') === 0)
				$field->setName(trim(str_replace($this->name . '[', '',  $field->Name), ']'));

			$field->PrependedName = false;
			$field->UnPrependedName = true;
		}
	}
} 