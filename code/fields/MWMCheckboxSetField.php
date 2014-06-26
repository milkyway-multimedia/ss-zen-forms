<?php
/**
 * Milkyway Multimedia Module
 * MWMCheckboxSetField.php
 *
 * Fix the checkbox set field to use proper field name
 * conventions, hence be compatible with some jQuery plugins (ie. jQuery.validate)
 *
 * @package mwm
 * @author Mell - Milkyway Multimedia
 */

class MWMCheckboxSetField extends CheckboxSetField {
	public $addValueToName = true;

	public function addValueToName($flag = true) {
		$this->addValueToName = $flag;
		return $this;
	}

	function Field($properties = array()) {
		Requirements::css(FRAMEWORK_DIR . '/css/CheckboxSetField.css');

		$source = $this->source;
		$values = $this->value;
		$items = array();

		// Get values from the join, if available
		if(is_object($this->form)) {
			$record = $this->form->getRecord();
			if(!$values && $record && $record->hasMethod($this->name)) {
				$funcName = $this->name;
				$join = $record->$funcName();
				if($join) {
					foreach($join as $joinItem) {
						$values[] = $joinItem->ID;
					}
				}
			}
		}
		
		// Source is not an array
		if(!is_array($source) && !is_a($source, 'SQLMap')) {
			if(is_array($values)) {
				$items = $values;
			} else {
				// Source and values are DataObject sets.
				if($values && is_a($values, 'SS_List')) {
					foreach($values as $object) {
						if(is_a($object, 'DataObject')) {
							$items[] = $object->ID;
						}
				   }
				} elseif($values && is_string($values)) {
					$items = explode(',', $values);
					$items = str_replace('{comma}', ',', $items);
				}
			}
		} else {
			// Sometimes we pass a singular default value thats ! an array && !SS_List
			if($values instanceof SS_List || is_array($values)) {
				$items = $values;
			} else {
				$items = explode(',', $values);
				$items = str_replace('{comma}', ',', $items);
			}
		}
			
		if(is_array($source)) {
			unset($source['']);
		}
		
		$odd = 0;
		$options = array();
		
		if ($source == null) $source = array();

		if($source) {
			foreach($source as $value => $item) {
				if($item instanceof DataObject) {
					$value = $item->ID;
					$title = $item->Title;
				} else {
					$title = $item;
				}

				$itemID = $this->ID() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $value);
				$odd = ($odd + 1) % 2;
				$extraClass = $odd ? 'odd' : 'even';
				$extraClass .= ' val' . preg_replace('/[^a-zA-Z0-9\-\_]/', '_', $value);

				$options[] = new ArrayData(array(
					'ID' => $itemID,
					'Class' => $extraClass,
					'Name' => $this->addValueToName ? "{$this->name}[{$value}]" : "{$this->name}[]",
					'Value' => $value,
					'Title' => $title,
					'isChecked' => in_array($value, $items) || in_array($value, $this->defaultItems),
					'isDisabled' => $this->disabled || in_array($value, $this->disabledItems)
				));
			}
		}

		$properties = array_merge($properties, array('Options' => new ArrayList($options)));

		return $this->customise($properties)->renderWith($this->getTemplates());
	}

	public function debug() {
		$val = is_array($this->value) ? implode(', ', $this->value) : (string) $this->value;
		return "$this->class ($this->name: $this->title : <font style='color:red;'>$this->message</font>)"
		. " = $val";
	}
}