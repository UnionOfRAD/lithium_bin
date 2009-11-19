<?php

namespace app\models;

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

	public static function create($data = 'latest') {
		if (!isset(static::$_views[$data])) {
			return false;
		}
		return parent::create(static::$_views[$data]);

	}


}

?>