<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminAntirequisites extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Manage antirequisites.
     */
    #[Route('/admin/antirequisites', name: 'admin_antirequisites')]
    public function antirequisitesAction()
    {
        $db = Zend_Registry::get('db');

        // Delete any requested item.
        if ($this->_getParam('delete')) {
            // Verify our CSRF key
            if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey()) {
                throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');
            }

            // Verify that this is a valid term.
            $deleteStmt = $db->prepare('DELETE FROM antirequisites WHERE subj_code = ? AND crse_numb = ? AND subj_code_eqiv = ? AND crse_numb_eqiv = ?');
            $deleteStmt->execute([
                $this->_getParam('subj_code'),
                $this->_getParam('crse_numb'),
                $this->_getParam('subj_code_eqiv'),
                $this->_getParam('crse_numb_eqiv'),
            ]);
        }

        // Add any chosen items.
        if ($this->_getParam('add')) {
            // Verify our CSRF key
            if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey()) {
                throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');
            }

            // Verify that this is a valid term.
            $insertStmt = $db->prepare('INSERT INTO antirequisites (subj_code, crse_numb, subj_code_eqiv, crse_numb_eqiv, added_by, comments) VALUES (?, ?, ?, ?, ?, ?)');
            foreach ($this->_getParam('equivalents_to_add') as $toAdd) {
                $params = explode('/', $toAdd);
                $params[] = $this->view->getUserDisplayName();
                $params[] = $this->_getParam($toAdd.'-comments');
                $insertStmt->execute($params);
            }
        }

        // Select our already-created antirequisites
        $data['antirequisites'] = $db->query('SELECT * FROM antirequisites ORDER BY subj_code, crse_numb, subj_code_eqiv, crse_numb_eqiv')->fetchAllAssociative();

        // Supply search results.
        $this->view->search_subj_code = $this->_getParam('search_subj_code');
        $this->view->search_crse_numb = $this->_getParam('search_crse_numb');
        if ($this->_getParam('search_subj_code') && $this->_getParam('search_crse_numb')) {
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
            $searchStmt->execute([
                $this->_getParam('search_subj_code'),
                $this->_getParam('search_crse_numb'),
                $this->_getParam('search_subj_code'),
                $this->_getParam('search_crse_numb'),
            ]);
            $this->view->searchResults = $searchStmt->fetchAll(PDO::FETCH_OBJ);
        }
    }
}
