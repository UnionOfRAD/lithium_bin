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
class Paste extends \lithium\core\StaticObject {

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
	protected static $_meta = array('source' => 'lithium_bin');

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
	protected static $_views = array(
		'latest' => array(
			'_id' => '_design/latest',
			'language' => 'javascript',
			'views' => array(
				'all' => array(
					'map' => 'function(doc) {
						var preview = String.substring(doc.content, 0, 100);
						emit(doc.author, {
							author:doc.author, language:doc.language,
							preview: preview, created: doc.created
						});
					}'
				)
			)
		)
	);

	/*
	* Validate the input data before saving to data
	* Validates author, content, language, permanent
	*
	* @return stdClass
	*/
	public static function validate($data) {
		if (!Validator::isAlphaNumeric($data->author)) {
			$data->errors['author'] =
				'This field can only be alphanumeric';
		}
		if (!Validator::isNotEmpty($data->content)) {
			$data->errors['content'] =
				'This field can not be left empty';
		}
		if (!in_array($data->language, static::$languages)) {
			$data->errors['language'] =
				'You have messed with the HTML that is not valid language';
		}
		return $data;
	}

	/**
	 * Saves the given data to the database
	 * Will automatically validate if not given a false 2nd parameter.
	 * If validation fails, will return with a 'validate' property set to
	 * false and 'errors' array of 'field' => 'error message'
	 *
	 * @param array $data request->data
	 * @param boolean $validate
	 * @return stdClass
	 */
	public static function save($data, $validate = true) {
		$defaults = array(
			'validates' => true, 'errors' => array(), 'saved' => false, 'parsed' => null
		);
		$data = (object) ($data[static::$alias] + $defaults + static::$_defaults);

		if ($validate && $data = static::validate($data)) {
			if (!empty($data->errors)) {
				$data->validates = false;
				return $data;
			}
		}
		$raw = $data->content;
		$data->content  = rawurlencode($data->content);

		if ($data->language != 'text' && in_array($data->language, static::$languages)) {
			$geshi = new GeSHi($raw, $data->language);
			$geshi->enable_classes();
			$geshi->enable_keyword_links(false);
			$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS,2);
			$data->parsed = rawurlencode($geshi->parse_code());
		}

		$data->created = date('Y-m-d H:i:s');

		$couch = Connections::get('couch');
		$result = $couch->post(static::$_meta['source'], $data);
		if (!$result->ok) {
			return $data;
		}
		$data->saved = true;
		$data->_id = $result->id;
		$data->_rev = $result->rev;
		return $data;
	}

	/**
	 * Create a stdClass data object with default values
	 * optionally include start values of your own.
	 *
	 * @param array $data of field values to start with
	 * @return stdClass a dataobject
	 */
	public static function create($data = array()) {
		$data += static::$_defaults;
		$data['created'] = date('Y-m-d h:m:s');
		$data += array('errors' => array());
		return (object) $data;
	}

	/**
	 * Find and return a dataobject for the given $id
	 * will parse data unless given option 'parsed' => false
	 *
	 * @param string $id uuid of the document for this paste
	 * @param array $options Valid keys are:
	 *		- parsed: A bool that if true will return geshi parsed code
	 * @return stdClass dataobject if found, null if
	 */
	public static function findFirstById($id, $options = array()) {
		$couch = Connections::get('couch');
		$result = $couch->get(static::$_meta['source'].'/'.$id);
		$result->content = rawurldecode($result->content);
		$result->parsed = rawurldecode($result->parsed);
		if (isset($result->error)) {
			return null;
		}
		return $result;
	}

	/**
	 * Direct access to the CouchDB view called 'latest'
	 * If the table (database to couchdb) is not present, it will create it.
	 * If that view is not present it, it will create it
	 *
	 * @param string $type
	 * @return stdClass object
	 */
	public static function latest($options = array()) {
		$couch = Connections::get('couch');
		$path = static::$_meta['source'] . '/' . static::$_views['latest']['_id'];
		$data = $couch->get($path . '/_view/all', $options);

		$isError = (
			isset($data->error) && $data->error == 'not_found'
		);
		if ($isError && $data->reason == 'no_db_file')  {
			$couch->put(static::$_meta['source']);
			return null;
		}
		if ($isError && in_array($data->reason, array('missing', 'deleted')))  {
			$create = $couch->put($path, static::$_views['latest']);
			$data = $couch->get($path . '/_view/all', $options);
		}
		foreach ($data->rows as $key => $row) {
			$data->rows[$key]->value->preview = rawurldecode($row->value->preview);
		}
		return $data;
	}
}
?>