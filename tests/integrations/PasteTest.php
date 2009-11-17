<?php

namespace app\tests\integrations;

use \lithium\data\Connections;
use \lithium\data\model\Query;

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

	protected $_meta = array(
		'key' => '_id',
		'source' => 'test_pastes',
		'connection' => 'test'
	);

	public function classes() {
		return $this->_classes;
	}
}


class PasteTest extends \lithium\test\Unit {

	public function setUpTasks($setUpTasks) {
		foreach ($setUpTasks as $task) {
			$this->{'_task'.$task}();
		}
	}

	public function tearDownTasks($tearDownTasks) {
		foreach ($tearDownTasks as $task) {
			$this->{'_task'.$task}();
		}
	}

	protected function _taskPutTable() {
		Connections::get("test")->put('/test_pastes');
	}

	protected function _taskFillTableSimple() {
		Connections::get("test")->put('/test_pastes/abcd1', array(
			'_id' => 'abcd1',
			'author' => 'alkemann',
			'content' => 'Lorem Ipsum'
		));
	}

	protected function _taskDeleteTable() {
        Connections::get("test")->delete(new Query(
       		array('model' => '\app\tests\integrations\MockPaste')
        ));
	}

	protected function _taskSaveOneRecord() {
		$data = array(
			'_id' => 'abcd1',
			'title' => 'Post',
			'content' => 'Lorem Ipsum',
			'author' => 'alkemann',
			'language' => 'text'
		);
		$paste = MockPaste::create($data);
		$paste->save();
	}

	public function testSave() {
		$this->setUpTasks(array('PutTable'));

		$data = array(
			'title' => 'Post',
			'content' => 'Lorem Ipsum',
			'author' => 'alkemann',
			'language' => 'text'
		);
		$paste = MockPaste::create($data);
		$result = $paste->save();

		$this->assertTrue($result);

		$this->setUpTasks(array('DeleteTable'));
	}

	public function testRead() {
		$this->setUpTasks(array('PutTable','SaveOneRecord'));

		$paste = MockPaste::find('abcd1');

		$result = $paste->exists();
		$this->assertTrue($result);

		$document = $paste->next();
		$data = $document->data();

		$expected = array(
			'_id','_rev','title','content',
			'author','language','parsed',
			'permanent','remember','created'
		);
		$result = array_keys($data);
		$this->assertEqual($expected, $result);

		$expected = 'alkemann';
		$result = $document->author;
		$this->assertEqual($expected, $result);

		$this->setUpTasks(array('DeleteTable'));
	}

	public function testReadNotFound() {
		$this->setUpTasks(array('PutTable'));

		$result = MockPaste::find('abcd1');
        $this->assertNull($result);

		$this->setUpTasks(array('DeleteTable'));
	}

}

?>