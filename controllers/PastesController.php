<?php

namespace app\controllers;

use \app\models\Paste;
use \app\models\PasteView;
use \lithium\storage\Session;
use \lithium\data\Connections;

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
	 * Run once (or until get OK message) to setup database)
	 * 
	 * @return array
	 */
	public function install() {
		Connections::get('default')->put('/'.Paste::meta('source'));
		PasteView::create()->save();
		$view = PasteView::find('_design/paste');
		return compact('view');
	}
	
	/**
	 * Asks the model for the data to be rendered at /latest
	 * showing the 10 latest pastes made.
	 *
	 * @return array
	 */
	public function index() {
		$options = array(
			'design' => 'paste', 'view' => 'all', 'limit' => 4, 'descending' => 'true'
		);
		$page = 1;
		if (isset($this->request->params['page'])) {
			if (isset($this->request->params['limit'])) {
				$options['limit'] = $this->request->params['limit'];
			}			
			$options['skip'] = ($this->request->params['page']-1) * $options['limit'];
			$page = $this->request->params['page'];
		}
		$limit = $options['limit'];
		$latest = Paste::find('all',array('conditions' => $options));	
		$total = Paste::find('count');
		return compact('latest','limit','page','total');
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
			$remember = $paste->remember;
			unset($paste->remember);
			if ($paste->save()) {
				if ($remember) {
				Session::write('paste', json_encode(array(
					'author' => $paste->author,
					'permanent' => ($paste->permanent == "1"),
					'remember' => true,
					'language' => $paste->language				
				)));
				} else {
					Session::write('paste', null);
				}
				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'view', 'args' => array($paste->id)
				));
			}
		}
		$languages = Paste::languages();
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
			$paste = Paste::find($this->request->data['id']);
			$remember = $paste->remember;
			unset($paste->remember);
			if ($paste && $paste->save($this->request->data)) {
				if ($remember) {
				Session::write('paste', json_encode(array(
					'author' => $paste->author,
					'permanent' => ($paste->permanent == "1"),
					'remember' => true,
					'language' => $paste->language				
				)));
				} else {
					Session::write('paste', null);
				}
				$this->redirect(array(
					'controller' => 'pastes', 'action' => 'view', 'args' => array($paste->id)
				));
			}
		}
		$languages = Paste::languages();
		$this->set(compact('paste', 'languages'));
		$this->render('form');
	}
}

?>