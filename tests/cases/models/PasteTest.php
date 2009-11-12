<?php

namespace app\tests\cases\models;

class MockPaste extends \app\models\Paste {

	protected $_meta = array(
		'source' => 'pastes',
		'connection' => 'test'
	);

}


class PasteTest extends \lithium\test\Unit {

	public function setUp() {
		MockPaste::schema();
	}
	
	public function tearDown() {
	
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
			'author',
			'parsed',
			'permanent',
			'remember',
			'language',
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
		
		$expected = ''; //date regex
		$result = $paste->created;		
		//$this->assertRegex($expected, $result);	
		
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
		
		$expected = array(
			'content' => 'This field can not be left empty'
		);
		$result = $paste->errors;
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
		$result = $paste->save(null, array('validate' => false));
		$this->assertTrue($result);	
	
	}
	
}

?>