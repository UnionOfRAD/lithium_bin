<?php

namespace app\controllers;

use \app\models\Paste;

/**
 * Controller that decides what data is available to the different actions (urls)
 * and what views that should be rendered. It does this by asking the Paste model
 * in the most generic way possible so as to not get involved with how the data is
 * handled.
 *
 * @link	http://rad-dev.org/lithium_bin
 * @package	lithium_bin
 * @author	alkemann
 */
class PastesController extends \lithium\action\Controller {

	/**
	 * Asks the model for the data to be rendered at /latest
	 * showing the 10 latest pastes made.
	 *
	 * @return array
	 */
	public function index() {
		return array('latest' => Paste::latest('all', array('limit' => 10)));
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

	/**
	 * Add paste
	 *
	 * @todo add cookie / session remembering of author name
     *
	 * @param string $author
	 * @param string $language
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
}

?>
