<?php

namespace app\tests\cases\models;

use \app\tests\mocks\models\MockPaste;
use \app\tests\mocks\models\MockPasteView;

class PasteViewTest extends \lithium\test\Unit {

	

	public function testCreate() {
		$view = MockPasteView::create();

		$result = $view instanceof \lithium\data\model\Document;
		$this->assertTrue($result);
		$this->skipIf(!$result, 'Not a Document result');

		$expected = '_design/paste';
		$result = $view->id;
		$this->assertEqual($expected, $result);

		$this->assertFalse(is_null($view->language));
		$this->assertFalse(is_null($view->views));
	}

	public function testCreateLatest() {
		$view = MockPasteView::create();

		$result = $view instanceof \lithium\data\model\Document;
		$this->assertTrue($result);
		$this->skipIf(!$result, 'Not a Document result');

		$expected = '_design/paste';
		$result = $view->id;
		$this->assertEqual($expected, $result);

		$expected = 'javascript';
		$result = $view->language;
		$this->assertEqual($expected, $result);

		$expected = array(
			'all' => array(
'map' => 'function(doc) {
	if (doc.permanent == "1") {
		emit(doc.created, {
			author: doc.author, language: doc.language,
			preview: doc.preview, created: doc.created
		});
	}
}'),
			'count' => array(
'map' => 'function(doc) {
	if (doc.permanent == "1") {
		emit(doc._id, null);
	}
}'),
		);
		$result = $view->views->data();
		$this->assertEqual($expected, $result);
	}
}

?>