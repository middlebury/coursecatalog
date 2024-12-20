<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminAntirequisites extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Manage antirequisites.
     */
    #[Route('/admin/antirequisites', name: 'list_antirequisites', methods: ['GET'])]
    public function antirequisitesAction(Request $request)
    {
        $data = [
            'searchResults' => [],
        ];
        $db = $this->entityManager->getConnection();

        // Select our already-created antirequisites
        $data['antirequisites'] = $db->executeQuery('SELECT * FROM antirequisites ORDER BY subj_code, crse_numb, subj_code_eqiv, crse_numb_eqiv')->fetchAllAssociative();

        // Supply search results.
        $data['search_subj_code'] = $request->get('search_subj_code');
        $data['search_crse_numb'] = $request->get('search_crse_numb');
        if ($request->get('search_subj_code') && $request->get('search_crse_numb')) {
            $searchStmt = $db->prepare(
                'SELECT
                    *,
                    subj_code IS NOT NULL AS antirequisite
                FROM
                    SCREQIV
                    LEFT JOIN antirequisites
                        ON (SCREQIV_SUBJ_CODE = subj_code AND SCREQIV_CRSE_NUMB = crse_numb
                            AND SCREQIV_SUBJ_CODE_EQIV = subj_code_eqiv AND SCREQIV_CRSE_NUMB_EQIV = crse_numb_eqiv)
                WHERE
                    (SCREQIV_SUBJ_CODE = ? AND SCREQIV_CRSE_NUMB = ?)
                    OR (SCREQIV_SUBJ_CODE_EQIV = ? AND SCREQIV_CRSE_NUMB_EQIV = ?)
                GROUP BY
                    SCREQIV_SUBJ_CODE, SCREQIV_CRSE_NUMB, SCREQIV_SUBJ_CODE_EQIV, SCREQIV_CRSE_NUMB_EQIV
                ORDER BY
                    SCREQIV_SUBJ_CODE, SCREQIV_CRSE_NUMB, SCREQIV_SUBJ_CODE_EQIV, SCREQIV_CRSE_NUMB_EQIV
                ');
            $searchStmt->bindValue(1, $request->get('search_subj_code'));
            $searchStmt->bindValue(2, $request->get('search_crse_numb'));
            $searchStmt->bindValue(3, $request->get('search_subj_code'));
            $searchStmt->bindValue(4, $request->get('search_crse_numb'));
            $result = $searchStmt->executeQuery();
            $data['searchResults'] = $result->fetchAllAssociative();
        }

        return $this->render('admin/antirequisites.html.twig', $data);
    }

    /**
     * Manage antirequisites.
     */
    #[Route('/admin/antirequisites/delete', name: 'delete_antirequisite', methods: ['POST'])]
    public function deleteAntirequisiteAction(Request $request)
    {
        $db = $this->entityManager->getConnection();

        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-antirequisites-delete', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        // Delete any requested item.
        $deleteStmt = $db->prepare('DELETE FROM antirequisites WHERE subj_code = ? AND crse_numb = ? AND subj_code_eqiv = ? AND crse_numb_eqiv = ?');
        $deleteStmt->bindValue(1, $request->get('subj_code'));
        $deleteStmt->bindValue(2, $request->get('crse_numb'));
        $deleteStmt->bindValue(3, $request->get('subj_code_eqiv'));
        $deleteStmt->bindValue(4, $request->get('crse_numb_eqiv'));
        $deleteStmt->executeQuery();

        return $this->redirect($this->generateUrl('list_antirequisites'));
    }

    /**
     * Manage antirequisites.
     */
    #[Route('/admin/antirequisites', name: 'add_antirequisites', methods: ['POST'])]
    public function addAntirequisitesAction(Request $request)
    {
        $db = $this->entityManager->getConnection();

        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-antirequisites-add', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        // Add any chosen items.
        $insertStmt = $db->prepare('INSERT INTO antirequisites (subj_code, crse_numb, subj_code_eqiv, crse_numb_eqiv, added_by, comments) VALUES (?, ?, ?, ?, ?, ?)');
        foreach ($request->get('equivalents_to_add') as $toAdd) {
            $params = explode('/', $toAdd);
            $insertStmt->bindValue(1, $params[0]);
            $insertStmt->bindValue(2, $params[1]);
            $insertStmt->bindValue(3, $params[2]);
            $insertStmt->bindValue(4, $params[3]);
            $insertStmt->bindValue(5, $this->getUser()->getName());
            $insertStmt->bindValue(6, $request->get($toAdd.'-comments'));
            $insertStmt->executeQuery();
        }

        return $this->redirect($this->generateUrl('list_antirequisites'));
    }
}
