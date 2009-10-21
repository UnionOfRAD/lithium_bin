<?php

namespace app\controllers;

use app\models\Paste;

class PastesController extends \lithium\action\Controller {

	/**
	 * Index
	 *
	 * @return void
	 */
	public function index() {
		return array('latest' => Paste::find('all', array('limit' => 10)));
	}

	/*
	 * Add paste
	 *
	 * @todo add cookie / session remembering of author name
	 */
	public function add($nick = null, $language = null) {
		if (empty($this->request->data)) {
			$paste = Paste::create(compact('nick', 'language'));
		} else {
			$paste = Paste::save($this->request->data);
			if ($paste->saved) {
				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'view', 'args' => array($paste->id)
				));
			}
		}
		$languages = Paste::$languages;
		return compact('paste', 'languages');
	}

	/**
	 * Controller action for url /view/[id]
	 *
	 * Verify that an id has been supplied, ask the model for it,
	 * verify that one was found, set the language (for layout),
	 * pass along the variables to view and layout
	 *
	 * @param string $id the uuid that the paste is saved under.
	 * @return array variables to pass to layout and view
	 */
	public function view($id = null) {
		$paste = Paste::findFirstById($id);
		if ($paste == null) {
			$this->redirect(array('controller' => 'pastes', 'action' => 'index'));
		}
		return compact('paste');
	}
}

?>