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
		return array('latest' => Paste::find('all', array('conditions'=> array(
			'design' => 'latest', 'view' => 'all', 'limit' => '10', 'descending' => 'true'
		))));
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
		$paste = Paste::find($id);
		if ($paste == null) {
			$this->redirect(array('controller' => 'pastes', 'action' => 'index'));
		}
		$binJs = true;
		return compact('paste','binJs');
	}

	/**
	 * Controller action for the urls that add pastes
	 * Will set up a form with default values and if given POST data through the
	 * Request object will post that tothe model for save. If save is successfull
	 * it will redirect to a view of the newly created paste.
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
			$paste = Paste::create($this->request->data);
			if ($paste->validates() && $paste->save()) {

				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'view', 'args' => array($paste->id)
				));
			}
		}
		$languages = Paste::$languages;
		$this->set(compact('paste', 'languages'));
		$this->render('form');
	}

	/**
	 * controller action for editing existing pastes. No user authentication or
	 * authorization required (new pastes will be new revisions). The action
	 * asks the model for the current values if not given POST data, otherwise
	 * it will ask the model to save it. If the save is succesful it will
	 * redirect to the view, if not, render the form again with the failed post
	 * data (dataobject will now include validation errors).
	 *
	 * @param string $id
	 * @return array
	 */
	public function edit($id = null) {
		if (empty($this->request->data)) {
			$paste = Paste::find($id);
			if ($paste == null) {
				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'add'
				));
			}
		} else {
			$paste = Paste::save($this->request->data);
			if ($paste->saved) {
				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'view', 'args' => array($paste->id)
				));
			}
		}
		$languages = Paste::$languages;
		$this->set(compact('paste', 'languages'));
		$this->render('form');
	}
}

?>