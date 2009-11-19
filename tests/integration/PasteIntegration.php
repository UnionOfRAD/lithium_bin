<?php

namespace app\tests\integration;

use \lithium\data\Connections;
use \lithium\data\model\Query;
use \app\tests\mocks\MockIntegrationPaste;
use \app\tests\mocks\MockIntegrationPasteView;

class PasteIntegration extends \lithium\test\Unit {

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

	protected function _taskDeleteTable() {
        Connections::get("test")->delete(new Query(
       		array('model' => '\app\tests\mocks\MockIntegrationPaste')
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
		$paste = MockIntegrationPaste::create($data);
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
		$paste = MockIntegrationPaste::create($data);
		$result = $paste->save();

		$this->assertTrue($result);

		$this->setUpTasks(array('DeleteTable'));
	}

	public function testRead() {
		$this->setUpTasks(array('PutTable','SaveOneRecord'));

		$paste = MockIntegrationPaste::find('abcd1');
		$result = $paste->exists();
		$this->assertTrue($result);

		$data = $paste->data();

		$expected = array(
			'id','title','content',
			'author','language','parsed',
			'permanent','remember','created','rev'
		);
		$result = array_keys($data);
		$this->assertEqual($expected, $result);

		$expected = 'alkemann';
		$result = $paste->author;
		$this->assertEqual($expected, $result);

		$this->setUpTasks(array('DeleteTable'));
	}
/* @todo Model and CouchDB adapters not compatible on not found
	public function testReadNotFound() {
		$this->setUpTasks(array('PutTable'));

		$result = MockIntegrationPaste::find('abcd1');
        $this->assertNull($result);

		$this->setUpTasks(array('DeleteTable'));
	}
*/
	public function testLatestView() {
		$this->setUpTasks(array('PutTable','FillTableFull'));

		$latest = MockIntegrationPaste::find('all', array('conditions'=> array(
			'design' => 'latest',
			'view' => 'all',
			'limit' => '10',
			'descending' => 'true'
		)));
		$this->assertNull($latest);

		$viewSave = MockIntegrationPasteView::create()->save();
		$this->skipIf(!$viewSave, 'Failed to save view. Tests skipped');

		$latest = MockIntegrationPaste::find('all', array('conditions'=> array(
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
