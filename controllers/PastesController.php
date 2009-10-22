<?php

namespace app\controllers;

use \app\models\Paste;

class PastesController extends \lithium\action\Controller {

	/**
	 * Index
	 *
	 * @return array
	 */
	public function index() {
		return array('latest' => Paste::latest('all', array('limit' => 10)));
	}

	/**
	 * Edit existing paste document
	 *
	 * @param string $id
	 * @return array
	 */
	public function edit($id = null) {
		if (empty($this->request->data)) {
			$paste = Paste::findFirstById($id);
			if ($paste == null) {
				$this->redirect(array('controller' => 'pastes', 'action' => 'add'));
			}
		} else {
			$paste = Paste::save($this->request->data);
			if ($paste->saved) {
				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'view', 'args' => array($paste->_id)
				));
			}
		}
		$languages = Paste::$languages;
		$this->set(compact('paste', 'languages'));
		$this->render('form');
	}

	/*
	 * Add paste
	 *
	 * @todo add cookie / session remembering of author name
	 */
	public function add($author = null, $language = null) {
		if (empty($this->request->data)) {
			$paste = Paste::create(compact('author', 'language'));
		} else {
			$paste = Paste::save($this->request->data);
			if ($paste->saved) {
				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'view', 'args' => array($paste->_id)
				));
			}
		}
		$languages = Paste::$languages;
		$this->set(compact('paste', 'languages'));
		$this->render('form');
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
		$binJs = true;
		return compact('paste','binJs');
	}
}

?>
