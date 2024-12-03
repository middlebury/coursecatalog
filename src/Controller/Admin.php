<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Admin extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * List Admin Screens.
     */
    #[Route('/admin', name: 'admin_index')]
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/markup', name: 'markup')]
    public function markupAction()
    {
        if (isset($_POST['sample_text']) && strlen($_POST['sample_text'])) {
            $this->view->sampleText = $_POST['sample_text'];
        } else {
            $this->view->sampleText = "This is some text. Shakespeare wrote /The Merchant of Venice/ as well as /Macbeth/. Words can have slashes in them such as AC/DC, but this does not indicate italics.\n\nSpaces around slashes such as this / don't cause italics either. Quotes may be /\"used inside slashes\",/ or \"/outside of them/\". *Bold Text* should have asterisk characters around it. Like slashes, * can be used surrounded by spaces, or surrounded by letters or numbers and not cause bold formatting: 4*5 = 20 or 4 * 5 = 20. Numbers as well as text can be bold *42* or italic /85/";
        }
        $this->view->sampleText = htmlspecialchars($this->view->sampleText);
        $this->view->output = banner_course_Course::convertDescription($this->view->sampleText);
    }

    #[Route('/admin/masquerade', name: 'masquerade')]
    public function masqueradeAction()
    {
        $masqueradeAuth = $this->_helper->auth->getMasqueradeHelper();

        if ($this->_getParam('masquerade')) {
            // Verify our CSRF key
            if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey()) {
                throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');
            }

            $masqueradeAuth->changeUser($this->_getParam('masquerade'));
            $this->_redirect('/', ['prependBase' => true, 'exit' => true]);
        }

        $this->view->userId = $this->_helper->auth()->getUserId();
        $this->view->userName = $this->_helper->auth()->getUserDisplayName();
    }
}
