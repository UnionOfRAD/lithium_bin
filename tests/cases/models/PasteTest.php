<?php

namespace app\tests\cases\models;

class MockPaste extends \app\models\Paste {

	protected $_meta = array(
		'source' => 'pastes',
		'connection' => 'test'
	);

	public function classes() {
		return $this->_classes;
	}
}


class PasteTest extends \lithium\test\Unit {

	public function setUp() {
		MockPaste::schema();
	}

	public function tearDown() {

	}

	public function testUsesDocument() {
		$paste = new MockPaste();

		$expected = array(
	      'query' => '\lithium\data\model\Query',
	      'record' => '\lithium\data\model\Document',
	      'validator' => '\lithium\util\Validator',
	      'recordSet' => '\lithium\data\model\RecordSet',
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
		$data = array(
			'title' => 'Post',
			'content' => 'Lorem Ipsum',
			'author' => 'alkemann',
			'language' => 'text'
		);
		$paste = MockPaste::create($data);
		$result = $paste->save();
		$this->assertTrue($result);
	}

	public function stestRead() {
		$paste = MockPaste::find('first', array(
			'_id' => 'dd5f119b503daccbfc07b0f2cfb549c2'
		));
		var_dump($paste);
	}

}

?>