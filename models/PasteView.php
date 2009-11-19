<?php

namespace app\models;

/**
 * This model is used to store Couch design views to the `Paste` database
 * It also defines it. Do not call a 'find' on this model. To view the view, use 
 * the 'design' condition in a 'find' call on the `Paste` model, ie :
 * {{{
 *		$latest = Paste::find('all', array('conditions' => array(
 *			'design' => 'latest,
 *			'limit' => 10
 *		)));
 * }}}
 *
 * When the find call in the example above returns a NULL, that means the view does not
 * exist in the `Paste` database. To insert it use:
 * {{{
 *		PasteView::create()->save();
 * }}}
 */
class PasteView extends \lithium\data\Model {

	/**
	 * Metadata
	 *
	 * @var array array of meta data to link the model with the couchdb datasource
	 *		- source : the name of the table (called database in couchdb)
	 */
	protected $_meta = array(
		'source' => 'lithium_bin'
	);

	/**
	 * Predefined views. Only used to store in db if not already there.
	 */
	protected static $_views = array(
		'latest' => array(
			'id' => '_design/latest',
			'language' => 'javascript',
			'views' => array(
				'all' => array(
'map' => 'function(doc) {
	if (doc.permanent == "1") {
		emit(doc.created, {
			author: doc.author, language: doc.language,
			preview: doc.preview, created: doc.created
		});
	}
}'
				)
			)
		),
	);

	/**
	 * Create a PasteView instance of Document
	 * Unlike Model::create, this takes a string name of a predefined design view
	 *
	 * @param string $data 'lastest' is only valid and default 
	 * @return Document
	 */
	public static function create($data = 'latest') {
		if (!isset(static::$_views[$data])) {
			return false;
		}
		return parent::create(static::$_views[$data]);

	}


}

?>