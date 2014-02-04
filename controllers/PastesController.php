<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

use app\models\Paste;
use app\models\PasteView;
use lithium\storage\Session;
use lithium\util\Set;

/**
 * Controller that decides what data is available to the different actions (urls)
 * and what views that should be rendered. It does this by asking the Paste model
 * in the most generic way possible so as to not get involved with how the data is
 * handled.
 *
 * @link	http://dev.lithify.me/lithium_bin
 * @package	lithium_bin
 */
class PastesController extends \lithium\action\Controller {

	/**
	 * Asks the model for the data to be rendered at /latest
	 * showing the 20 latest pastes made.
	 *
	 * @return array
	 */
	public function index() {
		$defaults = array('limit' => 20, 'order' => array('descending' => 'true'));
		$params = Set::merge($defaults, $this->request->params);
		list($limit, $order) = array($params['limit'], $params['order']);

		$latest = Paste::all(array(
			'conditions' => array(
				'design' => 'all', 'view' => 'pastes',
				'limit' => $params['limit']
			) + $params['order']
		));
		return compact('latest');
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
		if (!$paste = Paste::find($id)) {
			return $this->redirect('Pastes::index');
		}
		$paste->parsed = Paste::parse($paste->content, $paste->language);

		if ($this->request->type === 'txt' || $this->request->type === 'text') {
			return $paste->content;
		}
		return compact('paste');
	}

	/**
	 * Controller action for the urls that add pastes
	 * Will set up a form with default values and if given POST data through the
	 * Request object will post that tothe model for save. If save is successfull
	 * it will redirect to a view of the newly created paste.
	 *
	 * @param string $author
	 * @param string $language
	 */
	public function add($author = 'anonymous', $language = 'php') {
		if (empty($this->request->data)) {
			if ($saved = Session::read('paste')) {
				$data = (array) json_decode($saved);
			} else {
				$data = compact('author', 'language');
			}
			$paste = Paste::create($data);
		} else {
			$data =& $this->request->data;
			if (!empty($data['catch'])) {
				sleep(5);
				die('Caught!');
			}
			unset($data['catch']);

			if (isset($data['id'])) {
				$paste = Paste::find($data['id']);
				if (!$paste || $paste->immutable) {
					unset($paste);
				} else {
					$paste->set($data);
				}
			}

			if (!isset($paste)) {
				$paste = Paste::create($data);
			}
			if ($paste->save()) {
				$this->_remember($paste);
				return $this->redirect(array(
					'Pastes::view', 'args' => array($paste->id)
				));
			}
		}
		$this->render(array(
			'template' => 'form',
			'data' => array(
				'url' => 'Pastes::add',
				'languages' => Paste::languages()
			) + compact('paste')
		));
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
		if (!($paste = Paste::find($id)) || $paste->immutable) {
			return $this->redirect('Pastes::add');
		}

		$languages = Paste::languages();
		$url = array('controller' => 'pastes', 'action' => 'edit', 'args' => array($id));
		$this->set(compact('url', 'paste', 'languages'));
		$this->render(array(
			'template' => 'form',
			'data' => array(
				'url' => 'Pastes::add',
				'languages' => Paste::languages()
			) + compact('paste')
		));
}

	/**
	 * Remember the current user of the paste
	 *
	 * @param object $paste
	 * @return void
	 */
	protected function _remember($paste) {
		$data = null;

		if (!empty($this->request->data['remember'])) {
			$data = json_encode(array(
				'author' => $paste->author, 'remember' => true, 'language' => $paste->language
			));
		}
		Session::write('paste', $data);
	}
}

?>