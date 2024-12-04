<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Admin extends AbstractController
{
    /**
     * List Admin Screens.
     */
    #[Route('/admin', name: 'admin_index')]
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/markup', name: 'markup')]
    public function markupAction(Request $request)
    {
        $data = [];
        if ($request->get('sample_text') && strlen($request->get('sample_text'))) {
            $data['sampleText'] = $request->get('sample_text');
        } else {
            $data['sampleText'] = "This is some text. Shakespeare wrote /The Merchant of Venice/ as well as /Macbeth/. Words can have slashes in them such as AC/DC, but this does not indicate italics.\n\nSpaces around slashes such as this / don't cause italics either. Quotes may be /\"used inside slashes\",/ or \"/outside of them/\". *Bold Text* should have asterisk characters around it. Like slashes, * can be used surrounded by spaces, or surrounded by letters or numbers and not cause bold formatting: 4*5 = 20 or 4 * 5 = 20. Numbers as well as text can be bold *42* or italic /85/";
        }
        $data['sampleText'] = htmlspecialchars($data['sampleText']);
        $data['output'] = \banner_course_Course::convertDescription($data['sampleText']);

        return $this->render('admin/markup.html.twig', $data);
    }
}
