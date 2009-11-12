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
			'content' => 'Lorem Ipsum'
		);
		$result = MockPaste::create($data);
		
		$this->assertFalse($result->exists());
		
		$expected = array(
			'title' => 'Post',
			'content' => 'Lorem Ipsum',
			'author' => null,
			'parsed' => null,
			'permanent' => false,
			'remember' => false,
			'language' => null,
			'created' => '1979-07-26 08:05:00'		
		);
		$this->assertEqual($expected, $result->data());		
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