<?php

namespace app\models;

use \Geshi;
use \lithium\data\model\Document;
use \lithium\util\Validator;

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
		'permanent' => 0,
		'remember' => 0,
		'language' => 'text'
	);

	/**
	 * Views Document
	 */
	public static $_views = array(
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
		)
	);

	/**
	* Apply find and save filter
	*
	* Find filter :
	*  1 - For design view it will create the view if it doesnt exist and give null result
	*  2 - If the design view does exist, it will also rawurldecode the preview
	*  3 - For a find one result, it will rawurldecode content and parsed
	*
	* Save filter :
	*  1 - If the language submitted is in the valid list, it parses it with GeSHI
	*  2 - It will also rawurlencode both 'parsed' and 'content' fields
	*
	*/
	public static function __init($options = array()) {
		parent::__init($options);
		Paste::applyFilter('find', function($self, $params, $chain) {
			if (isset($params['options']['conditions']['design']) &&
					  $params['options']['conditions']['design'] == 'latest') {
				$conditions = $params['options']['conditions'];
				$result = $chain->next($self, $params, $chain);
				if ($result === null) {
					Paste::createView()->save();
					return null; //static::find('all', $conditions);
				}
				foreach ($result as $paste) {
					$paste->preview = rawurldecode($paste->preview);
				}
				return $result;
			} else {
				$result = $chain->next($self, $params, $chain);
				$result->preview = rawurldecode($result->preview);
				$result->content = rawurldecode($result->content);
				$result->parsed = rawurldecode($result->parsed);
				return $result;
			}
		});
		Paste::applyFilter('save', function($self, $params, $chain) {
			if ($params['record']->id != '_design/latest') {
				$document = $params['record'];
				if (in_array($document->language, Paste::$languages)) {
					$document = Paste::parse($document);
				}
				$document->preview = rawurlencode(substr($document->content,0,100));
				$document->parsed = rawurlencode($document->parsed);
				$document->content  = rawurlencode($document->content);
				$params['record'] = $document;
			}
			return $chain->next($self, $params, $chain);
		});
	}

	/**
	* Takes a reference to a Document, and parses the content
	*
	* @param Document $doc
	* @return Document
	*/
	public static function &parse(&$doc) {
		if (!($doc instanceof \lithium\data\model\Document)) {
			return null;
		}

		$geshi = new GeSHi($doc->content, $doc->language);
		$geshi->enable_classes();
		$geshi->enable_keyword_links(false);
		$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS,2);
		$doc->parsed = $geshi->parse_code();

		return $doc;
	}


	/**
	* Used to create and then save the design view 'latest' to couch, ie:
	* {{{
	* 	Paste::createView()->save();
	* }}}
	*
	* @return Document
	*/
	public static function createView() {
		return parent::create(static::$_views['latest']);
	}

	/**
	 *  Sets default values and calls the parent create()
	 *
	 * @param array $data of field values to start with
	 * @return Document
	 */
	public static function create($data = array()) {
		if (isset($data['Paste'])) {
			$data = $data['Paste'];
		}
		$data += static::$_defaults;
		if (!isset($data['created']))
			$data['created'] = date('Y-m-d h:i:s');
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