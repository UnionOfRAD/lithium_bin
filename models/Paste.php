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
	* Init method called by `Libraries::load()`. It applies filters on the save method.
	*
	* Filters are closure (inline functions) that are called in sequence ending with the
	* filtered method. As such they can insert themselves both before and after the filtered
	* method by placing logic either before or after their `$chain->next()` call.
	*
	* The filter parameters are:
	*	- `$self`	(string)	fully-namespaced class name.
	*	- `$params` (array)		an associative array of the params passed to the method
	*	- `$chain`  (Filters)	filters in line to be executed
	*
	* The filters return the same as the method they filter would, ie
	* 	- Find filter returns a modified Document instance
	*
	* @link http://li3.rad-dev.org/docs/lithium/util/collection/Filters
	* @param array $options Merged with the `meta` property, see `Paste::$_meta`
	*/
	public static function __init($options = array()) {
		parent::__init($options);
		Paste::applyFilter('save', function($self, $params, $chain) {
			$document = $params['record'];
			if (in_array($document->language, Paste::languages())) {
				$document = Paste::parse($document);
			}
			$document->preview = substr($document->content,0,100);
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

	/**
	 * Returns a list of languages that geshi can parse.
	 * Stored in static::$languages, but if empty will fill it by
	 * doing a Libraries::find()
	 *
	 * @return array
	 */
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
