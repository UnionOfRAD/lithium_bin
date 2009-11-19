<?php

namespace app\tests\integration;

use \lithium\data\Connections;
use \lithium\data\model\Query;
use \app\tests\mocks\MockIntegrationPasteView;

class PasteViewIntegration extends \lithium\test\Unit {

	public function testViewSave() {
		Connections::get("test")->put('/test_pastes');

		$result = MockIntegrationPasteView::create()->save();
		$this->assertTrue($result);

		Connections::get("test")->delete(new Query(
       		array('model' => '\app\tests\mocks\MockIntegrationPaste')
        ));
	}


}

?>