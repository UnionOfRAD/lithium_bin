<?php

namespace app\tests\cases\models;

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
       		array('model' => '\app\tests\cases\models\MockPaste')
        ));
	}

	public function testUsesDocument() {
		$paste = new MockPaste();

		$expected = array(
	      'query' => '\lithium\data\model\Query',
	      'record' => '\lithium\data\model\Document',
	      'validator' => '\lithium\util\Validator',
	      'recordSet' => '\lithium\data\model\Document',
	      'connections' => '\lithium\data\Connections'
		);
		$result = $paste->classes();
		$this->assertEqual($expected, $result);

		$doc = MockPaste::create();
		$this->assertTrue(is_a($doc, '\lithium\data\model\Document'));
	}

	public function testCreate() {
		$data = array(
			'title' => 'Post',
			'content' => 'Lorem Ipsum',
			'language' => 'text',
			'author' => 'alkemann'
		);
		$paste = MockPaste::create($data);

		$result = $paste->exists();
		$this->assertFalse($result);

		$expected = array(
			'title',
			'content',
			'language',
			'author',
			'parsed',
			'permanent',
			'remember',
			'created',
		);
		$result = array_keys($paste->data());
		$this->assertEqual($expected, $result);

		$expected = 'Post';
		$result = $paste->title;
		$this->assertEqual($expected, $result);

		$expected = 'Lorem Ipsum';
		$result = $paste->content;
		$this->assertEqual($expected, $result);

		$expected = 'text';
		$result = $paste->language;
		$this->assertEqual($expected, $result);

		$expected = 'alkemann';
		$result = $paste->author;
		$this->assertEqual($expected, $result);

		$this->assertNull($paste->parsed);
		$this->assertFalse($paste->permanent);
		$this->assertFalse($paste->remember);

		$expected = '(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})';
		$result = $paste->created;
		$this->assertPattern($expected, $result);

	}

	public function testValidation() {
		$data = array(
			'title' => 'Post',
			'content' => 'Lorem Ipsum',
			'author' => 'alkemann',
			'language' => 'text'
		);
		$paste = MockPaste::create($data);
		$result = $paste->validates();
		$this->assertTrue($result);

		$data = array(
			'title' => 'Post',
			'content' => '',
			'author' => 'alkemann',
			'language' => 'text'
		);
		$paste = MockPaste::create($data);
		$result = $paste->validates();
		$this->assertFalse($result);
	}

	public function testValidationErrors() {
		$data = array(
			'title' => 'Post',
			'content' => '',
			'author' => 'Tom Good',
			'language' => 'nose'
		);
		$paste = MockPaste::create($data);
		$result = $paste->validates();
		$this->assertFalse($result);

		$this->assertTrue(is_a($paste, '\lithium\data\model\Document'),
			'Paste isnt a Document');
		$this->skipIf(!is_a($paste, '\lithium\data\model\Document'));
		$this->assertTrue(is_a($paste->errors, '\lithium\data\model\Document'));
		$expected = array(
			'author' => 'This field can only be alphanumeric',
			'content' => 'This field can not be left empty',
			'language' => 'You have messed with the HTML that is not valid language'
		);
		$result = $paste->errors->data();
		$this->assertEqual($expected, $result);
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
		$this->setUpTasks(array('PutTable','FillTableSimple'));

		$paste = MockPaste::find('first', array('conditions' =>
			array('_id' => 'abcd1')
		));

		$result = $paste->exists();
		$this->assertTrue($result);

		$document = $paste->next();
		$data = $document->data();

		$expected = array('_id','_rev','author','content');
		$result = array_keys($data);
		$this->assertEqual($expected, $result);

		$expected = 'alkemann';
		$result = $document->author;
		$this->assertEqual($expected, $result);

		$this->setUpTasks(array('DeleteTable'));
	}

	public function testReadNotFound() {
		$this->setUpTasks(array('PutTable'));

		$result = MockPaste::find('first', array('conditions' =>
			array('_id' => 'abcd1')
		));
        $this->assertNull($result);

		$this->setUpTasks(array('DeleteTable'));
	}

	public function methods() {
		return array('testRead', 'testReadNotFound');
	}
}

?>