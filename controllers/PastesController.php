<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

use \app\models\Paste;
use \app\models\PasteView;
use \lithium\storage\Session;

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
		$page = 1;
		$limit = 10;
		$order = array('descending' => 'true');
		if (isset($this->request->params['page'])) {
			$page = $this->request->params['page'];
			if (!empty($this->request->params['limit'])) {
				$limit = $this->request->params['limit'];
			}
		}
		$conditions = array('design' => 'paste', 'view' => 'all', 'skip' => ($page - 1) * $limit);
		$total = Paste::find('count');
		$latest = Paste::find('all', compact('conditions', 'limit', 'order'));
		return compact('latest', 'limit', 'page', 'total');
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
		if ($paste->rewind() == 'not_found') {
			$this->redirect(array('controller' => 'pastes', 'action' => 'index'));
		}
		return compact('paste');
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
	public function add($author = null, $language = 'php') {
		if (empty($this->request->data)) {
			if ($saved = Session::read('paste')) {
				$data = (array) json_decode($saved);
			} else {
				$data = compact('author', 'language');
			}
			$paste = Paste::create($data);
		} else {
			$paste = Paste::create($this->request->data);
			if ($paste->save()) {
				$this->_remember($paste);
				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'view', 'args' => array($paste->id)
				));
			}
		}
		$languages = Paste::languages();
		$url = array('controller' => 'pastes', 'action' => 'add');
		$this->set(compact('url', 'paste', 'languages'));
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
				$this->redirect(array('controller' => 'pastes', 'action' => 'add'));
			}
		} else {
			$paste = Paste::find($this->request->data['id']);
			if ($paste && $paste->save($this->request->data)) {
				$this->_remember($paste);
				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'view', 'args' => array($paste->id)
				));
			}
		}
		$languages = Paste::languages();
		$url = array('controller' => 'pastes', 'action' => 'edit', 'args' => array($paste->id));
		$this->set(compact('url', 'paste', 'languages'));
		$this->render('form');
	}

	/**
	 * Remember the current user of the paste
	 *
	 * @param object $paste
	 * @return void
	 */
	protected function _remember($paste) {
		if (!empty($this->request->data['remember'])) {
			Session::write('paste', json_encode(array(
				'author' => $paste->author, 'remember' => true, 'language' => $paste->language
			)));
			return;
		}
		Session::write('paste', null);
	}
}

?>