<?php

namespace app\extensions\command;

use \app\models\Paste;
use \app\models\PasteView;

/**
 * Command to assist in setup and management of Lithium Bin
 *
 */
class Bin extends \lithium\console\Command {

	/**
	 * Run the install method to create database and views
	 *
	 * @return boolea
	 */
	public function install() {
		$this->header('Lithium Bin');
		$result = Paste::install();
		PasteView::create()->save();
		return $this->checkView();
	}
	
	public function update() {		
		$view = PasteView::find('_design/paste');
		if ($view && !isset($view->error)) {
			$view->delete();
		}
		PasteView::create()->save();	
		return $this->checkView();	
	}
	
	protected function checkView() {
		$view = PasteView::find('_design/paste');
		if (!empty($view->reason)) {
			switch($view->reason) {
				case 'no_db_file':
					$this->out(array(
						'Database does not exist.',
						'Please make sure CouchDB is running and refresh to try again.'
					));
				break;
				case 'missing':
					$this->out(array(
						'Database created.', 'Design views were not created.',
						'Please run the command again.'
					));
				break;
			}
		}
		if (isset($view->id) && $view->id == '_design/paste' && count($view->views) == 2) {
			$this->out('Installation successful.');
			return true;
		}
		return false;
	}

}

?>