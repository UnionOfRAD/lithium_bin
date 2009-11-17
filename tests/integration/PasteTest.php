<?php

namespace app\tests\integration;

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
		'key' => 'id',
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

	protected function _taskFillTableFull() {
		$data = array(
			'id' => 'a1',
			'author' => 'alkemann',
			'created' => '2009-01-01 01:01:10',
			'language' => 'text',
			'content' => 'Lorem Ipsum',
			'parsed' => '',
			'permanent' => true,
			'remember' => false,
			'saved' => false
		);
		$paste = MockPaste::create($data);
		$paste->save();
		$data['id'] = 'a2';
		$data['created'] = '2009-01-01 01:01:02';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['id'] = 'a3';
		$data['created'] = '2009-01-01 01:01:04';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['id'] = 'a4';
		$data['created'] = '2009-01-01 01:01:03';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['id'] = 'a5';
		$data['created'] = '2009-01-01 01:01:05';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['id'] = 'a6';
		$data['permanent'] = false;
		$data['created'] = '2009-01-01 01:01:07';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['permanent'] = true;
		$data['id'] = 'a7';
		$data['created'] = '2009-01-01 01:01:11';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['id'] = 'a8';
		$data['created'] = '2009-01-01 01:01:13';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['id'] = 'a9';
		$data['created'] = '2009-01-01 01:01:06';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['permanent'] = false;
		$data['id'] = 'a10';
		$data['created'] = '2009-01-01 01:01:09';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['permanent'] = true;
		$data['id'] = 'a11';
		$data['created'] = '2009-01-01 01:01:08';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['id'] = 'a12';
		$data['created'] = '2009-01-01 01:01:12';
		$paste = MockPaste::create($data);
		$paste->save();
		$data['id'] = 'a13';
		$data['created'] = '2009-01-01 01:01:01';
		$paste = MockPaste::create($data);
		$paste->save();
	}

	protected function _taskDeleteTable() {
        Connections::get("test")->delete(new Query(
       		array('model' => '\app\tests\integration\MockPaste')
        ));
	}

	protected function _taskSaveOneRecord() {
		$data = array(
			'id' => 'abcd1',
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
			'id','title','content',
			'author','language','parsed',
			'permanent','remember','created','rev'
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

	public function testLatestView() {
		$this->setUpTasks(array('PutTable','FillTableFull'));
		MockPaste::applyFilter('find', function($self, $params, $chain) {
			var_dump($params);
			if ($params['options']['conditions']['design'] = 'latest') {
				$conditions = $params['options']['conditions'];
				$result = $chain->next($self, $params, $chain);
				if ($result === null) {
					MockPaste::createView()->save();
					return null; //static::find('all', $conditions);
				}
				return $result;
			} else {
				return $chain->next($self, $params, $chain);
			}
		});
		$latest = MockPaste::find('all', array('conditions'=> array(
			'design' => 'latest',
			'view' => 'all',
			'limit' => '10',
			'descending' => 'true'
		)));
		$this->assertNull($latest);

		$latest = MockPaste::find('all', array('conditions'=> array(
			'design' => 'latest',
			'view' => 'all',
			'limit' => '10',
			'descending' => 'true'
		)));
		$result = $latest instanceof \lithium\data\model\Document;

		$this->assertTrue($result);
		$this->skipIf(!$result, 'Not a document result');


		$expected = 10;
		$result = sizeof($latest->data());
		$this->assertEqual($expected, $result);

		$first = $latest->rewind();
		$expected = 'a8';
		$result = $first->id;
		$this->assertEqual($expected, $result);

		$next = $latest->next();
		$expected = 'a12';
		$result = $next->id;
		$this->assertEqual($expected, $result);
		$next = $latest->next();

		$expected = 'a7';
		$result = $next->id;
		$this->assertEqual($expected, $result);

		$next = $latest->next();
		$expected = 'a1';
		$result = $next->id;
		$this->assertEqual($expected, $result);

		$next = $latest->next();
		$expected = 'a11';
		$result = $next->id;
		$this->assertEqual($expected, $result);

		$this->setUpTasks(array('DeleteTable'));
	}

}

?>