<?php
/**
 * @since 4/16/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines a few methods to allow course offering objects to get back to
 * other data from sessions such as terms and courses.
 *
 * @since 4/16/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface banner_course_SessionInterface
{
    /**
     * Answer a catalog database id string.
     *
     * @return string
     *
     * @since 4/20/09
     */
    public function getCatalogDatabaseId(osid_id_Id $id);

    /**
     * Answer the Id of the 'All'/'Combined' catalog.
     *
     * @return osid_id_Id
     *
     * @since 4/20/09
     */
    public function getCombinedCatalogId();

    /**
     * Answer a database-id for an Id object passed or throw an osid_NotFoundException
     * if the Id is not one that this implementation might know about.
     *
     * @param object osid_id_Id $id
     * @param string optional $prefix
     *
     * @return string
     *
     * @since 4/10/09
     */
    public function getDatabaseIdString(osid_id_Id $id, $prefix = null);

    /**
     * Answer an Id object from a string database Id.
     *
     * @param string $databaseId
     * @param string optional $prefix
     *
     * @return osid_id_Id
     *
     * @since 4/10/09
     */
    public function getOsidIdFromString($databaseId, $prefix = null);
}
