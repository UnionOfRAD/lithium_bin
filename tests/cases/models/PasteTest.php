<?php

namespace app\tests\cases\models;

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


class PasteTest extends \lithium\test\Unit {
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

	public function testCreateWithCreatedField() {
		$data = array(
			'title' => 'Post',
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

	public function testCreateView() {
		$view = MockPaste::create(array('design' => 'latest'));

		$expected = '_design/latest';
		$result = $view->id;
		$this->assertEqual($expected, $result);

		$expected = 'javascript';
		$result = $view->language;
		$this->assertEqual($expected, $result);

		$this->assertTrue(is_string($view->views->all->map));
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
			'author' => 'You forgot your alphanumeric name?',
			'content' => 'You seem to be missing the content.',
			'language' => 'Invalid language.'
		);
		$result = $paste->errors->data();
		$this->assertEqual($expected, $result);
	}
	/*
	public function testApplyingFilter() {
		MockPaste::applyFilter('save', function($self, $params, $chain) {
			$document = $params['record'];
			if ($document->language != 'text' &&
				 in_array($document->language, MockPaste::$languages)) {
				 	$document = MockPaste::mockParse($document);
			}
			return $document ;
		});

		$data = array(
			'content' => 'echo $this->function("lol");',
			'author' => 'TomGood',
			'language' => 'php'
		);
		$paste = MockPaste::create($data);
		$result = $paste->save();

		$expected = 'PARSED';
		$this->assertEqual($expected, $result->parsed);
	}
	*/
	public function testGeShiFilter() {
		MockPaste::applyFilter('save', function($self, $params, $chain) {
			$document = $params['record'];
			if ($document->language != 'text' &&
				 in_array($document->language, MockPaste::$languages)) {
				 	$document = \app\models\Paste::parse($document);
			}
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
