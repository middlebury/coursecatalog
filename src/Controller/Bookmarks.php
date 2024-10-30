<?php

namespace App\Controller;

use App\Service\Bookmarks as BookmarksService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Bookmarks extends AbstractController
{
    public function __construct(
        private BookmarksService $bookmarks,
    ) {
    }

    /**
     * Bookmark a course.
     */
    #[Route('/bookmarks/add/{courseId}', name: 'add_bookmark')]
    public function add(\osid_id_Id $courseId)
    {
        ob_start();
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<response>';
        try {
            if (!$courseId) {
                throw new \InvalidArgumentException('No Course Id specified.');
            }
            $this->bookmarks->add($courseId);
            echo '<success/>';
        } catch (\Exception $e) {
            echo '<error code="'.$e->getCode().'">'.$e->getMessage().'</error>';
        }
        echo '</response>';
        $response = new Response(ob_get_clean());
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }

    /**
     * Remove a course bookmark.
     *
     * @since 7/29/10
     */
    #[Route('/bookmarks/remove/{courseId}', name: 'remove_bookmark')]
    public function remove(\osid_id_Id $courseId)
    {
        ob_start();
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<response>';
        try {
            if (!$courseId) {
                throw new \InvalidArgumentException('No Course Id specified.');
            }
            $this->bookmarks->remove($courseId);
            echo '<success/>';
        } catch (\Exception $e) {
            echo '<error code="'.$e->getCode().'">'.$e->getMessage().'</error>';
        }
        echo '</response>';
        $response = new Response(ob_get_clean());
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }
}
