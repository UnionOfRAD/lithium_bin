<?php

namespace app\tests\mocks\models;

class MockIntegrationPasteView extends \app\models\PasteView {

	/**
	* @todo remove when Model problem with adapter is fixed in core
	*/
	protected $_classes = array(
	  'query' => 'lithium\data\model\Query',
	  'record' => 'lithium\data\model\Document',
	  'validator' => 'lithium\util\Validator',
	  'recordSet' => 'lithium\data\model\Document',
	  'connections' => 'lithium\data\Connections'
	);

	protected $_meta = array(
		'source' => 'test_pastes',
		'connection' => 'test'
	);

	public function classes() {
		return $this->_classes;
	}
}
?>