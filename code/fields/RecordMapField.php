<?php
/**
 * Milkyway Multimedia
 * RecordMapField.php
 *
 * @package
 * @author Mellisa Hankins <mellisa.hankins@me.com>
 */

class RecordMapField extends HiddenField {
	private static $allowed_classes = array(
		'Member',
	);

	private static $disable_map_fields = array(
		'ID',
		'ClassName',
		'Created',
		'LastEdited',
	);

	public $identifier;
	public $mapping = array();
	public $ignore = array();

	public $additionalData = array();
	public $additionalRelations = array();

	public $recordClass;
	public $record;

	public $requires;

	public $canCreate = true;
	public $canEdit = true;
	public $canDelete = false;

	public static function available_map_fields($class) {
		if(!in_array($class, self::config()->allowed_classes) || !ClassInfo::exists($class))
			return false;

		$available = Config::inst()->get($class, 'public_fields') ? Config::inst()->get($class, 'public_fields') : array_keys(singleton($class)->summaryFields());

		$fields = array_diff($available, self::config()->disable_map_fields);

		return count($fields) ? $fields : false;
	}

	public function __construct($name, $title = null, $value = null, $mapping = array(), $identifier = null) {
		if(ClassInfo::exists($name) && singleton($name) instanceof DataObject)
			$this->recordClass = $name;

		$this->mapping = $mapping;

		if(is_object($identifier))
			$this->record = $identifier;
		else
			$this->identifier = $identifier;

		$this->doNotMapTo(array());

		parent::__construct($name, $title, $value);
	}

	function doNotMapTo($fields) {
		$fields = (array)$fields;

		$this->ignore = array_merge($this->ignore, $fields, $this->config()->disable_map_fields);

		return $this;
	}

	public function setMapping($map) {
		$this->mapping = $map;
		return $this;
	}

	public function addMappedField($field, $recordField) {
		$this->mapping[$field] = $recordField;
		return $this;
	}

	public function removeMappedField($field) {
		if(isset($this->mapping[$field])) unset($this->mapping[$field]);
		return $this;
	}

	public function setRecordClass($class) {
		$this->recordClass = $class;
		return $this;
	}

	public function getRecordClass() {
		return $this->recordClass;
	}

	function saveInto(DataObjectInterface $record = null, $values = array()) {
		if(!$this->canSaveInto($record)) return null;

		$mapping = (array)$this->mapping;
		$identifier = $this->identifier;

		$id = null;
		$r = null;

		if($this->record)
			$r = $this->record;
		elseif($identifier && $this->recordClass) {
			if(($key = array_search($identifier, $mapping)) && $key !== false) {
				if($this->form && $this->form->fieldByName($identifier))
					$id = $this->form->fieldByName($identifier)->dataValue();
				elseif(count($values) && isset($values[$identifier]))
					$id = $values[$identifier];

				$identifier = $key;
			}
			elseif($this->form && $this->form->fieldByName($identifier))
				$id = $this->form->fieldByName($identifier)->dataValue();
			elseif(count($values) && isset($values[$identifier]))
				$id = $values[$identifier];
			elseif($record)
				$id = $record->$identifier;

			if($id)
				$r = DataObject::get($this->recordClass)->filter($identifier, $id)->first();
		}

		if(!$r && $this->canCreate)
			$r = Object::create($this->recordClass);

		// Do not edit admins through this field
		if(($r instanceof Member) && Permission::checkMember($r, 'ADMIN'))
			return null;

		if($r && $this->canEdit) {
			$data = array();

			if(count($mapping)) {
				foreach($mapping as $field => $formField) {
					if(!$formField) continue;

					if($this->form && $this->form->fieldByName($formField))
						$data[$field] = $this->form->fieldByName($formField)->dataValue();
					elseif(count($values) && isset($values[$formField]))
						$data[$field] = $values[$formField];
				}
			}
			else
				$data = $this->form ? $this->form->Data : $values;

			$data = $data + $this->additionalData;

			foreach($this->ignore as $field) {
				if(isset($data[$field])) unset($data[$field]);
			}

			foreach($this->additionalRelations as $relation => $objects) {
				if($r->hasMethod($relation)) {
					if(!is_array($objects)) $objects = array($objects);
					foreach($objects as $object) {
						if($r->$relation() instanceof SS_List)
							$r->$relation()->add($object);
						else {
							$relationID = $relation . 'ID';
							$r->$relationID = $object->ID;
						}
					}
				}
			}

			if(count($data)) {
				$r->castedUpdate($data)->write();
			}
		}

		return $r;
	}

	function canSaveInto($record = null) {
		if($this->requires) {
			if(!$this->form) return false;

			$requires = (array)$this->requires;

			foreach($requires as $r => $args) {
				if($args instanceof FormField) {
					if(!$args->hasData()) continue;
					if(!$args->dataValue()) return false;
				}
				elseif($this->form->Fields()->fieldByName($r)) {
					if(!$this->form->Fields()->fieldByName($r)->hasData()) continue;

					if(is_array($args) && isset($args['value']) && $this->form->Fields()->fieldByName($r)->dataValue() != $args['value'])
						return false;
					elseif($this->form->Fields()->fieldByName($r)->dataValue() != $args)
						return false;
					elseif(!$this->form->Fields()->fieldByName($r)->dataValue())
						return false;
				}
				elseif($this->form->Fields()->fieldByName($args)) {
					if(!$this->form->Fields()->fieldByName($args)->hasData()) continue;
					if(!$this->form->Fields()->fieldByName($args)->dataValue()) return false;
				}
			}
		}

		return true;
	}

	function Value() {
		return $this->Name;
	}
}