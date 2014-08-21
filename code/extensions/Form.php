<?php
/**
 * Milkyway Multimedia
 * Form.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */

namespace Milkyway\SS\ZenForms\Extensions;

class Form extends \Extension {
	private static $allowed_actions = array(
		'handleDefaultAction',
	);

	public $_defaultAction = null;

	/**
	 * save form data to session
	 */
	function saveToSession($data, $name = '') {
		if(!$name) $name = $this->owner->FormName();

		\Session::set("FormInfo.{$name}.data", $data);
	}

	/**
	 * load form data from session
	 */
	function loadFromSession($name = '') {
		if(!$name) $name = $this->owner->FormName();

		$formSession = \Session::get("FormInfo.{$name}.data");
		if($formSession) $this->owner->loadDataFrom($formSession);
	}

	/**
	 * clear current form data session
	 */
	function clearSessionData($name = '', $clearErrors = true){
		if(!$name) $name = $this->owner->FormName();
		\Session::clear("FormInfo.{$name}.data");
		if($clearErrors) \Session::clear("FormInfo.{$name}.errors");
	}

	public function addDefaultAction($name) {
		$actions = $this->owner->Actions();

		if($action = $actions->dataFieldByName('action_' . $name)) {
			$default = $action->castedCopy('FormAction')->setName('action_handleDefaultAction?action=' . urlencode($name))->addExtraClass('default-action');
			$this->_defaultAction = $action;
			$this->owner->FormDefaultAction = $default;
			$this->owner->Actions()->insertBefore($default, $actions->First()->Name);
		}
	}

	public function removeDefaultAction() {
		if($default = $this->_defaultAction) {
			$this->owner->Actions()->removeByName('action_handleDefaultAction');
			$this->_defaultAction = null;
			$this->owner->FormDefaultAction = null;
		}
	}

	public function handleDefaultAction($data, $form, $r) {
		$action = $this->_defaultAction ? $this->_defaultAction->Name : urldecode($data['action']);

		if(substr($action,0,7) == 'action_') {
			if(strpos($action,'?') !== false) {
				list($action, $paramVars) = explode('?', $action, 2);
				$newRequestParams = array();
				parse_str($paramVars, $newRequestParams);
				$data = array_merge((array)$data, (array)$newRequestParams);
			}

			$action = preg_replace(array('/^action_/','/_x$|_y$/'),'',$action);
		}

		if(!$action)
			return $form->httpError(404);

		if(
			// Ensure that the action is actually a button or method on the form,
			// and not just a method on the controller.
			$form->Controller->hasMethod($action)
			&& !$form->Controller->checkAccessAction($action)
			// If a button exists, allow it on the controller
			&& !$form->Actions()->dataFieldByName('action_' . $action)
		) {
			return $form->httpError(
				403,
				sprintf('Action "%s" not allowed on controller (Class: %s)', $action, get_class($form->Controller))
			);
		} elseif(
			$form->hasMethod($action)
			&& !$form->checkAccessAction($action)
			// No checks for button existence or $allowed_actions is performed -
			// all form methods are callable (e.g. the legacy "callfieldmethod()")
		) {
			return $form->httpError(
				403,
				sprintf('Action "%s" not allowed on form (Name: "%s")', $action, $form->Name)
			);
		}

		if($form->Controller->hasMethod($action)) {
			return $form->Controller->$action($data, $form, $r);
			// Otherwise, try a handler method on the form object.
		} elseif($form->hasMethod($action)) {
			return $form->$action($data, $form, $r);
		} elseif($field = $form->checkFieldsForAction($form->Fields(), $action)) {
			return $field->$action($data, $form, $r);
		}

		return $form->httpError(404);
	}
} 