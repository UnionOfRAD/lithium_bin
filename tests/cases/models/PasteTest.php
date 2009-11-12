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
		
		$expected = array(
			'content' => 'This field can not be left empty'
		);
		$result = $paste->errors;
		$this->assertEqual($expected, $result);	
		
		$data = array(
			'title' => 'Post',
			'content' => '',
			'author' => 'Tom Good',
			'language' => 'nose'
		);
		$paste = MockPaste::create($data);		
		$result = $paste->validates();
		$this->assertFalse($result);	
		
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
		var_dump($paste);
	}  
	
}

?>