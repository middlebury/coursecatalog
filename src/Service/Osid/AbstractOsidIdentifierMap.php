<?php

namespace App\Service\Osid;

/**
 * A helper to provide access to the CourseManager OSID and OSID configuration.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class AbstractOsidIdentifierMap
{
    /**
     * Set up the mapping service.
     *
     * @param string $idAuthorityToShorten
     *                                     The Identifier authority to shorten
     */
    public function __construct(
        private string $idAuthorityToShorten,
    ) {
    }

    /**
     * Answer the id-authority for whom ids should be shortened.
     *
     * @return mixed string or false if none should be shortened
     *
     * @since 6/16/09
     */
    protected function getIdAuthorityToShorten()
    {
        if (isset($this->idAuthorityToShorten)) {
            return $this->idAuthorityToShorten;
        } else {
            return false;
        }
    }
}
