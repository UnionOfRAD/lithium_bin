<?php

namespace app\models;

use \Geshi;
use lithium\core\Libraries;
use lithium\util\Validator;
use lithium\data\Connections;

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
	 *  Schema for Paste
	 *
	 * @var array
	 */
	protected $_schema = array(
		'author' 	=> array('default' => null, 'type' => 'string'),
		'content' 	=> array('default' => null, 'type' => 'string'),
		'parsed' 	=> array('default' => null, 'type' => 'string'),
		'permanent'	=> array('default' => false, 'type' => 'boolean'),
		'language' 	=> array('default' => 'text', 'type' => 'string'),
		'created' 	=> array('default' => '1979-01-01 01:01:01', 'type' => 'string')
	);

	/**
	* Validation rules for Paste fields
	*/
	public $validates = array(
		'content' => 'You seem to be missing the content.',
		'author' => array('alphaNumeric', 'message' => 'You forgot your alphanumeric name?'),
		'language' => array('validLanguage', 'message' => 'Invalid language.')
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
		$self = static::_object();

		Paste::applyFilter('save', function($self, $params, $chain) {
			$document = $params['entity'];
			if (!$document->id) {
				$document->created = date('Y-m-d h:i:s');
			}
			if (!empty($params['data'])) {
				$document->set($params['data']);
			}
			$document->parsed = Paste::parse($document->content, $document->language);
			$document->preview = substr($document->content, 0, 100);
			$document->modified = date('Y-m-d h:i:s');
			$params['entity'] = $document;
			return $chain->next($self, $params, $chain);
		});
		Validator::add('validLanguage', function ($value, $format, $options) {
			return (in_array($value, Paste::languages()));
		});
	}

	/**
 	* Takes a string and returns it parsed in the specified language
	*
	* @param string $content
	* @param string $langauge
	*/
	public static function parse($content, $language) {
		if (!in_array($language, static::languages())) {
			if (!in_array('text', static::languages())) {
				return $content;
			}
			$language = 'text';
		}
		$geshi = new GeSHi($content, $language);
		$geshi->enable_classes();
		$geshi->enable_keyword_links(false);
		$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS,2);
		return $geshi->parse_code();
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
}

?>