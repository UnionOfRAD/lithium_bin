<?php

namespace app\tests\cases\models;

use app\tests\mocks\models\MockPaste;

class PasteTest extends \lithium\test\Unit {

	public function testUsesDocument() {
		$paste = new MockPaste();

		$expected = array(
	      'query' => 'lithium\data\model\Query',
	      'record' => 'lithium\data\model\Document',
	      'validator' => 'lithium\util\Validator',
	      'recordSet' => 'lithium\data\model\Document',
	      'connections' => 'lithium\data\Connections'
		);
		$result = $paste->classes();
		$this->assertEqual($expected, $result);

		$doc = MockPaste::create();
		$this->assertTrue(is_a($doc, 'lithium\data\model\Document'));
	}

	public function testCreate() {
		$data = array(
			'content' => 'Lorem Ipsum',
			'language' => 'text',
			'author' => 'alkemann'
		);
		$paste = MockPaste::create($data);

		$result = $paste->exists();
		$this->assertFalse($result);

		$expected = array(
			'content',
			'language',
			'author',
			'parsed',
			'permanent',
			'created'
		);
		$result = array_keys($paste->data());
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

		$expected = '(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})';
		$result = $paste->created;
		$this->assertPattern($expected, $result);

	}

	public function testCreateWithCreatedField() {
		$data = array(
			'content' => 'Lorem Ipsum',
			'language' => 'text',
			'author' => 'alkemann',
			'created' => '2009-11-17 06:02:15'
		);
		$paste = MockPaste::create($data);

		$expected = $data['created'];
		$result = $paste->created;
		$this->assertEqual($expected, $result);
	}

	public function testValidation() {
		$data = array(
			'content' => 'Lorem Ipsum',
			'author' => 'alkemann',
			'language' => 'text'
		);
		$paste = MockPaste::create($data);
		$result = $paste->validates();
		$this->assertTrue($result);

		$data = array(
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
			'content' => '',
			'author' => 'Tom Good',
			'language' => ''
		);
		$paste = MockPaste::create($data);
		$result = $paste->validates();
		$this->assertFalse($result);

		$this->assertTrue(is_a($paste, 'lithium\data\model\Document'),
			'Paste isnt a Document');
		$this->skipIf(!is_a($paste, 'lithium\data\model\Document'));
		$result = $paste->errors();
		$this->assertTrue(is_array($result));
		$expected = array(
			'author' => array('You forgot your alphanumeric name?'),
			'content' => array('You seem to be missing the content.'),
			'language' => array('Invalid language.')
		);
		$this->assertEqual($expected, $result);

		$data = array(
			'author' => 'alpha',
			'content' => 'Lorem',
			'language' => 'notalanguage'
		);
		$paste = MockPaste::create($data);
		$result = $paste->validates();
		$this->assertFalse($result);

		$expected = array('language' => array('Invalid language.'));
		$result = $paste->errors();
		$this->assertEqual($expected, $result);
	}

	public function testGeShiFilter() {
		MockPaste::applyFilter('save', function($self, $params, $chain) {
			$document = $params['record'];
			$document->parsed = MockPaste::parse($document->content, $document->language);
			return $document ;
		});

		$data = array(
			'content' => 'echo',
			'author' => 'TomGood',
			'language' => 'php'
		);
		$paste = MockPaste::create($data);
		$doc = $paste->save();

		$expected = '<pre class="php" style="font-family:monospace;"><ol><li class="li1"><div class="de1"><span class="kw1">echo</span></div></li></ol></pre>';
		$result = $doc->parsed;
		$this->assertEqual($expected, $result);

	}

}

?>