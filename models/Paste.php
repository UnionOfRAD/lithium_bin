<?php

namespace app\models;

use \Geshi;
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
	public static $languages = array('php', 'diff', 'html', 'javascript', 'text');

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
	 * Views Document
	 */
	public static $_views = array(
		'latest' => array('id' => '_design/latest', 'language' => 'javascript',
			'views' => array(
				'all' => array('map' => 'function(doc) {
					if (doc.permanent == "1") {
						var preview = String.substring(doc.content, 0, 100);
						emit(doc.created, {
							author:doc.author, language:doc.language,
							preview: preview, created: doc.created
						});
					}
				}'),
			)
		),
	);

	/**
	* Apply find and save filter
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
				$result->content = rawurldecode($result->content);
				$result->parsed = rawurldecode($result->parsed);
				return $result;
			}
		});
		Paste::applyFilter('save', function($self, $params, $chain) {
			if ($params['record']->id != '_design/latest') {
				$document = $params['record'];
				if ($document->language != 'text' &&
					 in_array($document->language, Paste::$languages)) {
				 		$document = Paste::parse($document);
				}
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
	 *  Sets default values and calls the parent create()
	 *
	 * @param array $data of field values to start with
	 * @return Document
	 */
	public static function create($data = array()) {
		if (isset($data['design'])) {
			if (!isset(static::$_views[$data['design']])) {
				return false;
			}
			return parent::create(static::$_views[$data['design']]);
		}
		if (isset($data['Paste'])) {
			$data = $data['Paste'];
		}
		$data += static::$_defaults + array('created' => date('Y-m-d h:m:s'));
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
		if (in_array($record->language, static::$languages)) {
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