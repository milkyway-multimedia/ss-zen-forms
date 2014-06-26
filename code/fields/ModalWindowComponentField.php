<?php
/**
 * Milkyway Multimedia
 * ModalWindowComponentField.php
 *
 * @package
 * @author Mellisa Hankins <mellisa.hankins@me.com>
 */

class ModalWindowComponentField extends ComponentFieldHolder {
	/**
	 * @var FieldList
	 */
	protected $footer;

	/**
	 * @var Boolean
	 */
	protected $allowClose = true;

	/**
	 * @var FormField
	 */
	protected $modalTrigger;

	public function __construct($name, $children = null) {
		$this->addExtraClass('modal fade');
		parent::__construct($name, $children);
	}

	public function canClose($flag = true) {
		$this->allowClose = $flag;
		return $this;
	}

	public function setModalTrigger($button = null) {
		$this->modalTrigger = $button;
		return $this;
	}

	public function getModalTrigger() {
		return $this->modalTrigger;
	}

	public function addTrigger($content = '', $link = '#') {
		if(!$content) {
			if($this->Title())
				$content = _t('ModalWindow_ComponentField.OPEN', 'Open {name}', array('name' => $this->Title()));
			else
				$content = _t('ModalWindow_ComponentField.OPEN', 'Open {name}', array('name' => $this->name_to_label($this->Name)));
		}

		$this->modalTrigger = FormActionLink::create($this->Name. '-Trigger', $content, $link);

		return $this;
	}

	public function getAttributes() {
		$attrs = array(
			'data-modal' => true,
			'tabindex' => '-1',
			'aria-hidden' => true,
		);

		return array_merge(parent::getAttributes(), $attrs, $this->attributes);
	}

	/**
	 * Accessor method for $this->children
	 *
	 * @return FieldList
	 */
	public function getFooter() {
		return $this->footer;
	}

	/**
	 * @param FieldList|FormField|array $children
	 */
	public function setFooter($children) {
		if($children instanceof FieldList)
			$this->footer = $children;
		else
			$this->footer = FieldList::create($children);

		return $this;
	}

	public function Field($properties = array()) {
		if(!isset($properties['modalID']))
			$properties['modalID'] = $this->ID();

		if(!isset($properties['modalTitle']) && $this->Title())
			$properties['modalTitle'] = $this->Title();

		if(!isset($properties['modalAttributes']))
			$properties['modalAttributes'] = $this->JSONAttributesHTML;

		if(!isset($properties['modalNoClose']) && !$this->allowClose)
			$properties['modalNoClose'] = true;

		if(!isset($properties['modalInitButton']) && $this->modalTrigger) {
			if($this->modalTrigger instanceof FormActionLink)
				$this->modalTrigger->triggerModal($this->ID());

			if($this->modalTrigger instanceof FormField)
				$properties['modalInitButton'] = $this->modalTrigger;
			else
				$properties['modalInit'] = $this->modalTrigger;
		}

		if(!isset($properties['modalFields']) && $this->children)
			$properties['modalFields'] = $this->children;

		if(!isset($properties['modalFooterFields']) && $this->footer)
			$properties['modalFooterFields'] = $this->footer;

		return parent::Field($properties);
	}
} 