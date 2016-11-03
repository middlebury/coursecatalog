<?php

/**
 * Copyright (c) 2009 Middlebury College.
 *
 *     Permission is hereby granted, free of charge, to any person
 *     obtaining a copy of this software and associated documentation
 *     files (the "Software"), to deal in the Software without
 *     restriction, including without limitation the rights to use,
 *     copy, modify, merge, publish, distribute, sublicesne, and/or
 *     sell copies of the Software, and to permit the persons to whom the
 *     Software is furnished to do so, subject the following conditions:
 *
 *     The above copyright notice and this permission notice shall be
 *     included in all copies or substantial portions of the Software.
 *
 *     The Software is provided "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *     EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 *     OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 *     NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 *     HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *     WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *     OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 *     DEALINGS IN THE SOFTWARE.
 *
 * @package phpkit.resource
 */

/**
 *  <p>A record for a <code> Resource. </code> The methods specified by the
 *  record type are available through the underlying object. </p>
 *
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:person_names
 *
 * @package phpkit.resource
 */
interface middlebury_resource_Resource_PersonNamesRecord
	extends osid_resource_ResourceRecord
{

	/**
	 * Gets the given (first) name of a person
	 *
	 * @return string
	 * @access public
	 * @compliance mandatory This method must be implemented.
	 */
	public function getGivenName ();

	/**
	 * Gets the surname (family name/last name) of a person
	 *
	 * @return string
	 * @access public
	 * @compliance mandatory This method must be implemented.
	 */
	public function getSurname ();

	/**
	 * Gets the middle name[s] of a person separated by spaces.
	 *
	 * @return string
	 * @access public
	 * @compliance mandatory This method must be implemented.
	 */
	public function getMiddleNames ();

	/**
	 * Gets the middle initial[s] of a person with any appropriate punctuation.
	 *
	 * @return string
	 * @access public
	 * @compliance mandatory This method must be implemented.
	 */
	public function getMiddleInitials ();

	/**
	 * Gets any suffix non-title suffix of a person that would appear after their name.
	 * E.g. 'Junior', 'Jr.', 'Sr.', 'III'
	 *
	 * @return string
	 * @access public
	 * @compliance mandatory This method must be implemented.
	 */
	public function getNameSuffix ();

	/**
	 * Gets any title of a person that would appear before their name. E.g. 'Mr.',
	 * 'Dr.', 'Miss', 'Admiral', etc.
	 *
	 * @return string
	 * @access public
	 * @compliance mandatory This method must be implemented.
	 */
	public function getPrefixTitle ();

	/**
	 * Gets any title of a person that would appear after their name. E.g. 'Ph.D.',
	 * 'Esquire', etc.
	 *
	 * @return string
	 * @access public
	 * @compliance mandatory This method must be implemented.
	 */
	public function getSuffixTitle ();

}
