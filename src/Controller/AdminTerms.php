<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminTerms extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Manage term visibility.
     */
    #[Route('/admin/terms', name: 'admin_terms_list', methods: ['GET'])]
    public function termsAction(Request $request)
    {
        $data = [];
        $db = $this->entityManager->getConnection();

        $searches = $db->executeQuery('SELECT * FROM catalog_term_match')->fetchAllAssociative();
        $catalogs = [];
        $queries = [];
        foreach ($searches as $search) {
            $catalogs[] = $search['catalog_id'];
            $queries[] =
"	SELECT
        '".$search['catalog_id']."' AS catalog,
        STVTERM_CODE,
        STVTERM_DESC
    FROM
        STVTERM
    WHERE
        STVTERM_CODE LIKE ('".$search['term_code_match']."')";
        }
        $union = implode("\n\tUNION\n", $queries);

        $query =
"SELECT
    t.*,
    IF(i.term_code, 1, 0) AS manually_disabled,
    count(SSBSECT_CRN) AS num_sections
FROM
    (\n".$union."\n\t) AS t
    LEFT JOIN catalog_term_inactive i ON (STVTERM_CODE = i.term_code AND i.catalog_id = ?)
    LEFT JOIN course_catalog c ON i.catalog_id = c.catalog_id
    LEFT JOIN ssbsect_scbcrse s ON (STVTERM_CODE = SSBSECT_TERM_CODE
        AND SCBCRSE_COLL_CODE IN (
            SELECT coll_code
            FROM course_catalog_college
            WHERE catalog_id = ?
        )
        AND SSBSECT_SSTS_CODE = 'A'
        AND (c.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != c.prnt_ind_to_exclude)
        )
WHERE
    catalog = ?
GROUP BY
    STVTERM_CODE
ORDER BY
    catalog ASC, STVTERM_CODE DESC";
        $stmt = $db->prepare($query);

        $data['catalogs'] = array_unique($catalogs);

        if ($request->get('catalog') && in_array($request->get('catalog'), $data['catalogs'])) {
            $catalog = $request->get('catalog');
        } else {
            $catalog = $data['catalogs'][0];
        }
        $stmt->bindValue(1, $catalog);
        $stmt->bindValue(2, $catalog);
        $stmt->bindValue(3, $catalog);
        $result = $stmt->executeQuery();
        $data['selectedCatalog'] = $catalog;
        $data['terms'] = $result->fetchAllAssociative();
        foreach ($data['terms'] as &$term) {
            $term['active'] = intval($term['num_sections']) && !intval($term['manually_disabled']);
        }

        $data['page_title'] = 'Manage Term Visibility';

        return $this->render('admin/terms.html.twig', $data);
    }

    /**
     * Manage term visibility.
     */
    #[Route('/admin/terms', name: 'admin_terms_update', methods: ['POST'])]
    public function termUpdateAction(Request $request)
    {
        $db = $this->entityManager->getConnection();

        if ($request->get('change_visibility')) {
            // Verify our CSRF key
            if (!$this->isCsrfTokenValid('admin-terms-update', $request->get('csrf_key'))) {
                throw new AccessDeniedException('Invalid CSRF key.');
            }

            // Verify that this is a valid term.
            $catalog = $request->get('catalog');
            $term = $request->get('term');
            $verifyStmt = $db->prepare('SELECT COUNT(*) FROM STVTERM WHERE STVTERM_CODE = ?');
            $verifyStmt->bindValue(1, $term);
            $result = $verifyStmt->executeQuery();
            $valid = (int) $result->fetchOne();
            $result->free();
            if (!$valid) {
                throw new \InvalidArgumentException('Invalid term-code: '.$term);
            }

            // Disable the term
            if ('true' == $request->get('disabled')) {
                $visibilityStmt = $db->prepare('INSERT INTO catalog_term_inactive (catalog_id, term_code) VALUES (?, ?);');
            }
            // Enable the term
            else {
                $visibilityStmt = $db->prepare('DELETE FROM catalog_term_inactive WHERE catalog_id = ? AND term_code = ?;');
            }
            $visibilityStmt->bindValue(1, $catalog);
            $visibilityStmt->bindValue(2, $term);
            $visibilityStmt->executeQuery();
        }

        return $this->redirect($this->generateUrl('admin_terms_list', ['catalog' => $catalog]));
    }
}
