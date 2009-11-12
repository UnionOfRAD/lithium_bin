<?php

namespace app\models;

use \Geshi;
use \lithium\util\Validator;
use \lithium\data\Connections;

/**
 * Data model for access the pastebin documents.
 * In this instance it works as an inbetween of the controller
 * and the CouchDB adapter. The goal being that the controller
 * can ask for data in a very generic way.
 *
 *
 * @link	http://rad-dev.org/lithium_bin
 * @package	lithium_bin
 * @author	alkemann
 */
class Paste extends \lithium\data\Model {

	/**
	 * public name of the model
	 *
	 * @var string
	 */
	public static $alias = 'Paste';

	/**
	 * Available languages
	 *
	 * @var array
	 */
	public static $languages = array('php','html','javascript','text');

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
	 *  Default values for document based db
	 *
	 * @todo remove 'remember' field when cookie logic is implemented
	 * @var array
	 */
	protected static $_defaults = array(
		'author' => null,
		'content' => null,
		'parsed' => null,
		'permanent' => false,
		'remember' => false,
		'language' => null,
		'created' => '1979-07-26 08:05:00'
	);

	/**
	 * Views Document
	 */
	public static $_views = array(
		'latest' => array(
			'_id' => '_design/latest',
			'language' => 'javascript',
			'views' => array(
				'all' => array(
					'map' => 'function(doc) {
						if (doc.permanent == "1") {
							var preview = String.substring(doc.content, 0, 100);
							emit(Date.parse(doc.created), {
								author:doc.author, language:doc.language,
								preview: preview, created: doc.created
							});
						}
					}'
				)
			)
		)
	);

	/**
	 *  Sets default values and calls the parent create()
	 *
	 * @param array $data of field values to start with
	 * @return Document
	 */
	public static function create($data = array()) {
		$data += static::$_defaults;
		$data['created'] = date('Y-m-d h:m:s');  
		return parent::create($data);
	}
	
	/*
	* Validate the input data before saving to data
	* Validates author, content, language, permanent
	*
	* @param $record Document instance
	* @param $options array
	* @return boolean
	*/
	public function validates($record, $options = array()) {
		$success = true; $errors = array();
		if (!Validator::isAlphaNumeric($record->author)) {
			$success = false;
			$errors['author'] = 'This field can only be alphanumeric';
		}
		if (!Validator::isNotEmpty($record->content)) {
			$success = false;
			$errors['content'] = 'This field can not be left empty';
		}
		if (!in_array($record->language, static::$languages)) {
			$success = false;
			$errors['language'] = 'You have messed with the HTML that is not valid language';
		}
		if (!$success) 
			$record->set(array('errors' => $errors));
		return $success;
	}
  
}
?>