<?php

/**
 * A helper to prepend url paths with the protocol and host name to make them
 * absolute URLs.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_PathAsAbsoluteUrl
	extends Zend_View_Helper_Abstract
{

	/**
	 * Answer a URL path as an absolute URL.
	 *
	 * @param string $path
	 * @return string
	 * @access public
	 * @since 6/9/10
	 */
	public function pathAsAbsoluteUrl ($path) {
		$scheme = 'http';
		if (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME']) {
			$scheme = $_SERVER['REQUEST_SCHEME'];
		}
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$scheme = 'https';
		}
		return $scheme . '://' . $_SERVER['HTTP_HOST'] . $path;
	}

}
