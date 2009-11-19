<?php

namespace app\tests\mocks;

class MockPaste extends \app\models\Paste {

	/**
	* @todo remove when Model problem with adapter is fixed in core
	*/
	protected $_classes = array(
	  'query' => '\lithium\data\model\Query',
	  'record' => '\lithium\data\model\Document',
	  'validator' => '\lithium\util\Validator',
	  'recordSet' => '\lithium\data\model\Document',
	  'connections' => '\lithium\data\Connections'
	);

	protected $_meta = array();

	public function classes() {
		return $this->_classes;
	}

	public static function &mockParse(&$doc) {
		if (!($doc instanceof \lithium\data\model\Document)) {
			return null;
		}
		$doc->parsed = 'PARSED';
		return $doc;
	}
}

?>