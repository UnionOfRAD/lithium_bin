<?php

namespace app\models;

use \Geshi;
use \lithium\core\Libraries;
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
	 * Available languages
	 *
	 * @var array
	 */
	public static $languages = null;

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
	 * Error messages for validation
	 *
	 * @var array
	 */
	protected static $_errors = array(
		'author' => 'You forgot your alphanumeric name?',
		'content' => 'You seem to be missing the content.',
		'language' => 'Invalid language.'
	);

	/**
	* Apply find and save filter
	*
	* Find filter :
	*  1 - If the design view does exist, it will also rawurldecode the preview
	*  2 - For a find one result, it will rawurldecode content and parsed
	*
	* Save filter :
	*  1 - If the language submitted is in the valid list, it parses it with GeSHI
	*  2 - It will also rawurlencode both 'parsed' and 'content' fields
	*
	*/
	public static function __init($options = array()) {
		parent::__init($options);
		Paste::applyFilter('find', function($self, $params, $chain) {
			if (isset($params['options']['conditions']['design'])) {
				$result = $chain->next($self, $params, $chain);
				if ($result === null) {
					return null;
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
			$document = $params['record'];
			if (in_array($document->language, Paste::languages())) {
				$document = Paste::parse($document);
			}
			$document->preview = rawurlencode(substr($document->content,0,100));
			$document->parsed = rawurlencode($document->parsed);
			$document->content  = rawurlencode($document->content);
			$params['record'] = $document;
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

	public static function languages() {
		if (static::$languages === null) {
			static::$languages =  Libraries::find('geshi', array(
				'path' => '/geshi', 'filter' => false, 'format' => function($class) {
					return basename($class, '.php');
				}
			));
		}
		return static::$languages;
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
		$errors = static::$_errors;

		if (Validator::isAlphaNumeric($record->author)) {
			unset($errors['author']);
		}
		if (Validator::isNotEmpty($record->content)) {
			unset($errors['content']);
		}
		if (in_array($record->language, static::languages())) {
			unset($errors['language']);
		}
		if (empty($errors)){
			return true;
		}
		$record->set(array('errors' => $errors));
		return false;
	}

}

?>