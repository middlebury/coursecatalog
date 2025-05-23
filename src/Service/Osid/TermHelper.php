<?php

namespace App\Service\Osid;

use Psr\Cache\CacheItemPoolInterface;

/**
 * A helper to with functions for handling terms.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class TermHelper
{
    /**
     * @var \DateTime
     *                The date to reference as "now" when calculating
     *                past/current/future
     */
    private $referenceDate;

    /**
     * @param runtime $runtime
     *                               The Osid Runtime service
     * @param idMap   $idMap
     *                               The Osid IdMap service
     * @param string  $referenceDate
     *                               A date string to reference as "now" when calculating
     *                               past/current/future. This should be of a date-string
     *                               supported by DateTime.
     */
    public function __construct(
        private Runtime $runtime,
        private IdMap $idMap,
        private CacheItemPoolInterface $cache,
        ?string $referenceDate = 'now',
    ) {
        $this->referenceDate = new \DateTime($referenceDate);
    }

    /**
     * Answer the "current" termId for the catalog passed.
     *
     * If multiple terms overlap to be 'current', only one will be returned.
     *
     * @return osid_id_Id the current term id
     *
     * @throws osid_NotFoundException
     *
     * @since 6/11/09
     */
    public function getNextOrLatestTermId(\osid_id_Id $catalogId)
    {
        $cacheKey = $this->osidIdToCacheKey('upcoming_term', $catalogId);
        $cacheItem = $this->cache->getItem($cacheKey);
        $currentTerm = $cacheItem->get();
        if (empty($currentTerm)) {
            $manager = $this->runtime->getCourseManager();
            if (!$manager->supportsTermLookup()) {
                throw new \osid_NotFoundException('Could not determine a current term id. The manager does not support term lookup.');
            }
            $termLookup = $manager->getTermLookupSessionForCatalog($catalogId);
            $currentTerm = $this->findNextOrLatestTermId($termLookup->getTerms(), $this->referenceDate);
            if (!$currentTerm) {
                throw new \osid_NotFoundException('Could not determine an upcoming term id for the catalog passed.');
            }

            $cacheItem->set($currentTerm);
            $this->cache->save($cacheItem);
        }

        return $currentTerm;
    }

    /**
     * Answer the "current" termId for the catalog passed. If multiple terms overlap
     * to be 'current', only one will be returned.
     *
     * @return osid_id_Id the current term id
     *
     * @throws osid_NotFoundException
     *
     * @since 6/11/09
     */
    public function getCurrentTermId(\osid_id_Id $catalogId)
    {
        $cacheKey = $this->osidIdToCacheKey('current_term', $catalogId);
        $cacheItem = $this->cache->getItem($cacheKey);
        $currentTerm = $cacheItem->get();
        if (empty($currentTerm)) {
            $manager = $this->runtime->getCourseManager();
            if (!$manager->supportsTermLookup()) {
                throw new \osid_NotFoundException('Could not determine a current term id. The manager does not support term lookup.');
            }
            $termLookup = $manager->getTermLookupSessionForCatalog($catalogId);
            $currentTerm = $this->findClosestTermId($termLookup->getTerms(), $this->referenceDate);
            if (!$currentTerm) {
                throw new \osid_NotFoundException('Could not determine a current term id for the catalog passed.');
            }

            $cacheItem->set($currentTerm);
            $this->cache->save($cacheItem);
        }

        return $currentTerm;
    }

    private function osidIdToCacheKey(string $prefix, \osid_id_Id $id)
    {
        $idString = $this->idMap->toString($id);

        return str_replace('\\', '-', str_replace(':', '_', str_replace('.', ',', $prefix.'--'.$idString)));
    }

    /**
     * Answer the term id whose start time is nearest in the future or latest if none are in the future.
     *
     * @param optional dateTime $date The date to reference the terms to
     *
     * @return osid_id_Id
     *
     * @since 2/07/13
     */
    public function findNextOrLatestTermId(\osid_course_TermList $terms, ?\DateTime $date = null)
    {
        $upcomingIds = [];
        $upcomingDates = [];
        $pastIds = [];
        $pastDates = [];

        if (null === $date) {
            $date = time();
        } else {
            $date = (int) $date->format('U');
        }

        if (!$terms->hasNext()) {
            throw new \osid_NotFoundException('Could not determine a current term id. No terms found.');
        }

        while ($terms->hasNext()) {
            $term = $terms->getNextTerm();
            $start = (int) $term->getStartTime()->format('U');

            // If the term starts in the future, add it to the upcoming list
            if ($start > $date) {
                $upcomingIds[] = $term->getId();
                $upcomingDates[] = $start;
            }
            // Otherwise, add it to our past terms
            else {
                $pastIds[] = $term->getId();
                $pastDates[] = $start;
            }
        }

        // If we have an upcoming term, return the one that is soonest in the future.
        if (count($upcomingIds)) {
            array_multisort($upcomingDates, \SORT_NUMERIC, \SORT_ASC, $upcomingIds);

            return $upcomingIds[0];
        }
        // Otherwise return the most recent past term
        else {
            array_multisort($pastDates, \SORT_NUMERIC, \SORT_DESC, $pastIds);

            return $pastIds[0];
        }
    }

    /**
     * Answer the term id whose timespan is closest to now.
     *
     * @param optional dateTime $date The date to reference the terms to
     *
     * @return osid_id_Id
     *
     * @since 6/11/09
     */
    public function findClosestTermId(\osid_course_TermList $terms, ?\DateTime $date = null)
    {
        $ids = [];
        $diffs = [];

        if (null === $date) {
            $date = time();
        } else {
            $date = (int) $date->format('U');
        }

        if (!$terms->hasNext()) {
            throw new \osid_NotFoundException('Could not determine a current term id. No terms found.');
        }

        while ($terms->hasNext()) {
            $term = $terms->getNextTerm();
            $start = (int) $term->getStartTime()->format('U');
            $end = (int) $term->getEndTime()->format('U');

            // If our current time is within the term timespan, return that term's id.
            if ($date >= $start && $date <= $end) {
                return $term->getId();
            }

            $ids[] = $term->getId();
            $diffs[] = abs($date - $start) + abs($date - $end);
        }

        array_multisort($diffs, \SORT_NUMERIC, \SORT_ASC, $ids);

        return $ids[0];
    }
}
