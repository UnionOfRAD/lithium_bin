<?php

namespace app\models;

/**
 * This model is used to store Couch design views to the `Paste` database
 * It also defines it. Do not call a 'find' on this model. To view the view, use
 * the 'design' condition in a 'find' call on the `Paste` model, ie :
 * {{{
 *     $latest = Paste::find('all', array(
 *         'conditions' => array(
 *             'design' => 'paste', 'view' => 'all'
 *          ),
 *         'limit' => 10
 *     ));
 * }}}
 *
 * When the find call in the example above returns a NULL, that means the view does not
 * exist in the `Paste` database. To insert it use:
 * {{{
 *     PasteView::create()->save();
 * }}}
 */
class PasteView extends \lithium\data\Model {

	/**
	 * Predefined views. Only used to store in db if not already there.
	 */
	public static $views = array(
		'all' => array(
			'id' => '_design/all',
			'language' => 'javascript',
			'views' => array(
				'pastes' => array(
					'map' => 'function(doc) {
						if (!doc.private) {
							emit(doc.created, doc);
						}
					}'
				)
			)
		)
	);
}

?>