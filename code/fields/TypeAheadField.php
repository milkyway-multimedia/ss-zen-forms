<?php
/**
 *
 * TypeAheadField.php
 *
 *
 *
 * @package mwm-
 * @author Mell <mell@milkywaymultimedia.com.au>
 */

class TypeAheadField extends TextField {
	private static $allowed_actions = array(
		'suggestion',
		'prefetch',
	);

	/**
	 * Name of the class this field searches
	 *
	 * @var string
	 */
	protected $sourceClass;

	/**
	 * List filter (you can also use Title:StartsWith etc)
	 * If none provided, will use search context on data object
	 *
	 * @var string|array
	 */
	protected $sourceField = null;

	/**
	 * Name of the field to use to display as suggestion
	 *
	 * @var string
	 */
	protected $refField = 'Title';

	/**
	 * Name of the field to use as the value
	 *
	 * @var string
	 */
	protected $valField = 'ID';

	/**
	 * List used to search in database (if not provided, uses source class and source field instead
	 * Will also accept a URL to override field suggestion
	 *
	 * @var SS_List|array|string|Closure
	 */
	protected $sourceList;

	/**
	 * The url to use as the live search source
	 *
	 * @var string|boolean
	 */
	protected $suggestURL;

	/**
	 * Maximum numder of search results to display per search
	 *
	 * @var integer
	 */
	protected $limit = 10;

	/**
	 * Minimum number of characters that a search will act on
	 *
	 * @var integer
	 */
	protected $minSearchLength = 2;

	/**
	 * Flag indicating whether a selection must be made from the existing list.
	 * By default free text entry is allowed.
	 *
	 * @var boolean
	 */
	protected $requireSelection = false;

	/**
	 * Prefetch a number of results so user has some to select from
	 * Will also accept a URL to override field prefetch
	 *
	 * @var integer|String
	 */
	protected $prefetch = 10;

	function __construct($name, $title = null, $value = '', $sourceList = null, $sourceField = null, $refField = 'Title', $valField = 'ID') {
		// set source
		$this->sourceList  = $sourceList;
		$this->sourceField = $sourceField;

		$this->refField = $refField;
		$this->valField = $valField;

		// construct the TextField
		parent::__construct($name, $title, $value);
	}

	function setSourceClass($value) {
		$this->sourceClass = $value;

		return $this;
	}

	function setSourceField($value) {
		$this->sourceField = $value;

		return $this;
	}

	function setSourceList($value) {
		$this->sourceList = $value;

		return $this;
	}

	function getSourceList() {
		if ($this->sourceList && is_string($this->sourceList))
			return null;

		if (!$this->sourceList) {
			if ($class = $this->SourceClass)
				$this->sourceList = DataList::create($class);
		}

		return $this->sourceList;
	}

	public function getSourceClass() {
		if ($class = $this->sourceClass)
			return $class;

		$form = $this->getForm();
		if (!$form)
			return null;

		$record = $form->getRecord();
		if (!$record)
			return null;

		return $record->ClassName;
	}

	function getSuggestURL() {
		if ($this->sourceList && is_string($this->sourceList))
			return $this->sourceList;

		return $this->suggestURL !== null ? $this->suggestURL : $this->Link('suggestion');
	}

	function setSuggestURL($val = null) {
		$this->suggestURL = $val;

		return $this;
	}

	function getPrefetchURL() {
		if ($this->prefetch && is_string($this->prefetch))
			return $this->prefetch;

		return $this->prefetch ? $this->Link('prefetch') : null;
	}

	function setPrefetch($val = null) {
		$this->prefetch = $val;

		return $this;
	}

	function setMinimumSearchLength($val = 2) {
		$this->minSearchLength = $val;

		return $this;
	}

	function requireSelection($flag = true) {
		$this->requireSelection = $flag;
		return $this;
	}

	function getAttributes() {
		return array_merge(
			array(
				'data-suggest-url'       => $this->SuggestURL ? $this->SuggestURL . '?q=%QUERY' : false,
				'data-prefetch-url'      => $this->PrefetchURL,
				'data-min-length'        => $this->minSearchLength,
				'data-require-selection' => $this->requireSelection,
				'data-name'              => strtolower($this->ID()),
				'data-templates--empty'  => _t('TypeAheadField.NO_MATCHES', 'No matches found'),
			) + parent::getAttributes() + array(
				'autocomplete' => 'off'
			)
		);
	}

	function Field($properties = array()) {
		Requirements::javascript(MWM_DIR . '/thirdparty/typeahead/typeahead.bundle.js');
		Requirements::javascript(MWM_DIR . '/javascript/mwm.typeahead.js');

		return parent::Field($properties);
	}

	function suggestion(HTTPRequest $r) {
		$results = array();

		$list = $this->SourceList;

		if (!$list) {
			$response = new SS_HTTPResponse(json_encode($results), 200, 'fail');
			$response->addHeader('Content-type', 'application/json');

			return $response;
		}

		if ($this->limit === false)
			$limit = null;
		else
			$limit = $this->limit ? $this->limit : 10;

		// input
		$results = $this->results(Convert::raw2sql($r->getVar('q')), $list, null, $limit);

		$response = new SS_HTTPResponse(json_encode($results), 200, '');
		$response->addHeader('Content-type', 'application/json');

		return $response;
	}

	function prefetch(HTTPRequest $r) {
		if ($this->prefetch === true)
			$limit = null;
		else
			$limit = $this->prefetch ? $this->prefetch : 10;

		if ($list = $this->SourceList)
			$results = $this->results('', $list, null, $limit);
		else
			$results = array();

		$response = new SS_HTTPResponse(json_encode($results), 200, '');
		$response->addHeader('Content-type', 'application/json');

		return $response;
	}

	public function results($q = '', $list = null, $class = null, $limit = 10) {
		$list = $list ? $list : $this->SourceList;

		if ($list instanceof Closure)
			$list = $list($q);

		$class = $class ? $class : ($list && !is_array($list)) ? $list->dataClass() : $this->SourceClass;

		if (is_array($list))
			$results = $this->filterArray($q, $list, $class, $limit);
		else
			$results = $this->filterList($q, $list, $class, $limit);

		return $results;
	}

	public function filterArray($q, $list, $class, $limit = 10) {
		$results     = array();
		$noOfResults = 0;

		if($search  = $this->scaffoldSearchFields($class)) {
			$context = explode(':', reset($search));
			$pattern = '';

			if ($q && isset($context[1])) {
				switch ($context[1]) {
					case 'StartsWith':
						$pattern = '/^' . $q . '/';
						break;
					case 'EndsWith':
						$pattern = '/' . $q . '$/';
						break;
					default:
						$pattern = '/' . $q . '/';
						break;
				}
			}
		}
		else
			$pattern = '/^' . $q . '/';

		foreach ($list as $key => $item) {
			if ($limit && $noOfResults >= $limit)
				break;

			if (is_array($item)) {
				$result = $this->filterArray($q, $item, $class, $limit);

				if ($noOfResult = count($result)) {
					if ($limit && ($noOfResults + $noOfResult) > $limit)
						array_splice($result, 0, ($noOfResult - $noOfResults));

					$noOfResults += $noOfResult;
					$results[] = $this->resultGroupToMap($key, $result);
				}
			}
			else {
				$value = is_string($item) ? $item : $key;
				if (!is_string($value))
					continue;

				if ($pattern && preg_match($pattern, $value)) {
					$results[] = $this->resultToMap($key, $value);
					$noOfResults++;
				}
				else {
					$results[] = $this->resultToMap($key, $value);
					$noOfResults++;
				}
			}
		}

		return $results;
	}

	public function filterList($q, $list, $class, $limit = 10) {
		$results = array();

		$search = $this->scaffoldSearchFields($class);
		$params = array();

		if($q) {
			foreach ($search as $field) {
				$name          = (strpos($field, ':') !== false) ? $field : "$field:StartsWith";
				$params[$name] = $q;
			}

			$resulting = $list
				->filterAny($params)
				->sort(strtok($search[0], ':'), 'ASC')
				->limit($limit);
		}
		else {
			$resulting = $list
				->sort(strtok($search[0], ':'), 'ASC')
				->limit($limit);
		}

		if ($resulting->exists())
			$results = $this->resultsToMap($resulting);

		return $results;
	}

	public function resultsToMap($list, $valField = 'ID', $refField = 'Title') {
		$valField = $this->valField ? $this->valField : $valField;
		$refField = $this->refField ? $this->refField : $refField;

		$results = array();

		foreach ($list as $result)
			$results[] = $this->resultToMap($result->$valField, $result->$refField);

		return $results;
	}

	public function resultToMap($id, $text, $keyField = 'id', $valField = 'text') {
		return array(
			$keyField  => $id,
			$valField  => $text,
			'disabled' => in_array($id, $this->disabledOptions),
			'locked'   => in_array($id, $this->lockedOptions),
		);
	}

	public function resultGroupToMap($title, $children, $valField = 'text') {
		return array(
			$valField  => $title,
			'children' => $children,
		);
	}

	public function validate($validator) {
		if ($this->requireSelection) {
			$results = $this->results($this->value);

			if (!$results || !count($results)) {
				$validator->validationError(
					$this->name,
					_t('TypeAheadField.INVALID', 'Invalid value'),
					'validation',
					false
				);

				return false;
			}
		}

		return parent::validate($validator);
	}

	protected function scaffoldSearchFields($dataClass) {
		if ($this->sourceField)
			return $this->sourceField;

		$obj    = singleton($dataClass);
		$fields = null;

		if ($fieldSpecs = $obj->searchableFields()) {
			$customSearchableFields = $obj->config()->searchable_fields;
			foreach ($fieldSpecs as $name => $spec) {
				if (is_array($spec) && array_key_exists('filter', $spec)) {
					if (!$customSearchableFields || array_search($name, $customSearchableFields))
						$filter = 'StartsWith';
					else
						$filter = preg_replace('/Filter$/', '', $spec['filter']);

					$fields[] = "{$name}:{$filter}";
				}
				else
					$fields[] = $name;
			}
		}

		if (is_null($fields)) {
			if ($obj->hasDatabaseField('Title'))
				$fields = array('Title');
			elseif ($obj->hasDatabaseField('Name'))
				$fields = array('Name');
		}

		return $fields;
	}
} 