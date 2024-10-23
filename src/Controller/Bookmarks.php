<?php


namespace App\Controller;

use App\Service\Bookmarks as BookmarksService;
use App\Service\Osid\IdMap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Bookmarks extends AbstractController
{

    public function __construct(
        private BookmarksService $bookmarks,
        private IdMap $osidIdMap,
    ) {
    }

    /**
     * Bookmark a course.
     *
     * @return
     */
    #[Route('/bookmarks/add/{course}', name: 'add_bookmark')]
    public function add(string $course)
    {
        ob_start();
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<response>';
        try {
            if (!$course) {
                throw new \InvalidArgumentException('No Course Id specified.');
            }
            $this->bookmarks->add($this->osidIdMap->fromString($course));
            echo '<success/>';
        }
        catch (\Exception $e) {
            echo '<error code="' . $e->getCode() . '">' . $e->getMessage() . '</error>';
        }
        echo '</response>';
        $response = new Response(ob_get_clean());
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    /**
     * Remove a course bookmark.
     *
     * @return
     *
     * @since 7/29/10
     */
     #[Route('/bookmarks/remove/{course}', name: 'remove_bookmark')]
     public function remove(string $course)
     {
        ob_start();
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<response>';
        try {
            if (!$course) {
                throw new \InvalidArgumentException('No Course Id specified.');
            }
            $this->bookmarks->remove($this->osidIdMap->fromString($course));
            echo '<success/>';
        }
        catch (\Exception $e) {
            echo '<error code="' . $e->getCode() . '">' . $e->getMessage() . '</error>';
        }
        echo '</response>';
        $response = new Response(ob_get_clean());
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }
}
