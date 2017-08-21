<?php
/**
 * A small helper to convert a text title to an in-document link reference.
 */
class Catalog_View_Helper_TextToLink
	extends Zend_View_Helper_Abstract
{
	function textToLink($text) {
		return preg_replace('/[^a-z0-9.:]+/i', '-', $text);
	}
}
