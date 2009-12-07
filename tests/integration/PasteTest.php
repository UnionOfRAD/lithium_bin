<?php

namespace app\tests\integration;

use \lithium\data\Connections;
use \lithium\data\model\Query;
use \app\tests\mocks\models\MockIntegrationPaste;
use \app\tests\mocks\models\MockIntegrationPasteView;

class PasteTest extends \lithium\test\Unit {


	public function testSave() {
		$this->_tasks(array('PutTable'));

		$data = array(
			'content' => 'Lorem Ipsum',
			'author' => 'alkemann',
			'language' => 'text'
		);
		$paste = MockIntegrationPaste::create($data);
		$this->assertTrue($paste->save());

		$this->_tasks(array('DeleteTable'));
	}

	public function testSaveUpdate() {
		$this->_tasks(array('PutTable','SaveOneRecord'));

		$paste = MockIntegrationPaste::find('abcd1');
		$data = $paste->data();

		$paste2 = MockIntegrationPaste::find('abcd1');
		$data = $paste2->data();
		$data['content'] = 'EDIT';
		$this->assertTrue($paste2->save($data));

		$paste3 = MockIntegrationPaste::find('abcd1');
		$expected = 'EDIT';
		$result = $paste3->content;
		$this->assertEqual($expected, $result);

		$this->_tasks(array('DeleteTable'));
	}

	public function testRead() {
		$this->_tasks(array('PutTable','SaveOneRecord'));

		$paste = MockIntegrationPaste::find('abcd1');
		$this->assertTrue($paste->exists());

		$expected = array(
			'id','content',
			'author','language','parsed',
			'permanent','created','rev'
		);
		$result = array_keys($paste->data());
		$this->assertEqual($expected, $result);

		$expected = 'alkemann';
		$result = $paste->author;
		$this->assertEqual($expected, $result);

		$this->_tasks(array('DeleteTable'));
	}

	public function testReadNotFound() {
		$this->_tasks(array('PutTable'));

		$result = MockIntegrationPaste::find('abcd1');

		$this->assertFalse($result->exists());

		$this->_tasks(array('DeleteTable'));
	}

	public function testLatestView() {
		$this->_tasks(array('PutTable','FillTableFull'));

		$latest = MockIntegrationPaste::find('all', array('conditions'=> array(
			'design' => 'paste',
			'view' => 'all',
			'limit' => '10',
			'descending' => 'true'
		)));
		$this->assertFalse($latest->exists());

		$viewSave = MockIntegrationPasteView::create()->save();
		$this->skipIf(!$viewSave, 'Failed to save view. Tests skipped');

		$latest = MockIntegrationPaste::find('all', array('conditions'=> array(
			'design' => 'paste',
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

		$this->_tasks(array('DeleteTable'));
	}

	public function testCount() {
		$this->_tasks(array('PutTable','FillTableFull'));
		
		$expected = 11;
		$result = MockIntegrationPaste::find('count');
		$this->assertEqual($expected, $result);		
		
		$this->_tasks(array('DeleteTable'));
	}
	
	/** TEST SETUPS **/

	protected function _tasks($tasks) {
		foreach ($tasks as $task) {
			$this->{'_task'.$task}();
		}
	}

	protected function _taskPutTable() {
		Connections::get("test")->put('/test_pastes');
		MockIntegrationPasteView::create()->save();
	}

	protected function _taskDeleteTable() {
        Connections::get("test")->delete(new Query(
       		array('model' => '\app\tests\mocks\models\MockIntegrationPaste')
        ));
	}

	protected function _taskSaveOneRecord() {
		$data = array(
			'id' => 'abcd1',
			'content' => 'Lorem Ipsum',
			'author' => 'alkemann',
			'language' => 'text'
		);
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
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
			'saved' => false
		);
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['id'] = 'a2';
		$data['created'] = '2009-01-01 01:01:02';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['id'] = 'a3';
		$data['created'] = '2009-01-01 01:01:04';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['id'] = 'a4';
		$data['created'] = '2009-01-01 01:01:03';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['id'] = 'a5';
		$data['created'] = '2009-01-01 01:01:05';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['id'] = 'a6';
		$data['permanent'] = false;
		$data['created'] = '2009-01-01 01:01:07';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['permanent'] = true;
		$data['id'] = 'a7';
		$data['created'] = '2009-01-01 01:01:11';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['id'] = 'a8';
		$data['created'] = '2009-01-01 01:01:13';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['id'] = 'a9';
		$data['created'] = '2009-01-01 01:01:06';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['permanent'] = false;
		$data['id'] = 'a10';
		$data['created'] = '2009-01-01 01:01:09';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['permanent'] = true;
		$data['id'] = 'a11';
		$data['created'] = '2009-01-01 01:01:08';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['id'] = 'a12';
		$data['created'] = '2009-01-01 01:01:12';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
		$data['id'] = 'a13';
		$data['created'] = '2009-01-01 01:01:01';
		$paste = MockIntegrationPaste::create($data);
		$paste->save();
	}

}

?>