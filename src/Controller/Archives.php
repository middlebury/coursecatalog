<?php

namespace App\Controller;

use App\Service\Archive\ArchiveDirectoryInterface;
use App\Service\Archive\ArchiveFileInterface;
use App\Service\Archive\ArchiveStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class Archives extends AbstractController
{
    public function __construct(
        private ArchiveStorage $archiveStorage,
    ) {
    }

    /**
     * View a directory listing or archive file.
     */
    #[Route('/archive/{path}', name: 'view_archive', requirements: ['path' => Requirement::CATCH_ALL])]
    public function view(string $path = '')
    {
        $item = $this->archiveStorage->get($path);

        $data['page_title'] = $item->basename();
        if ('' == $path) {
            $data['page_title'] = 'Catalog Archives';
        }

        $data['breadcrumbs'] = [
            [
                'uri' => $this->generateUrl('home'),
                'label' => 'Course Catalog',
            ],
            [
                'uri' => $this->generateUrl('view_archive', ['path' => '']),
                'label' => 'Catalog Archives',
            ],
        ];
        if ('' != $path) {
            $pathParts = explode('/', $item->path());
            $bcPath = '';
            foreach ($pathParts as $level) {
                $bcPath = empty($bcPath) ? $level : $bcPath.'/'.$level;
                $data['breadcrumbs'][] = [
                    'uri' => $this->generateUrl('view_archive', ['path' => $bcPath]),
                    'label' => $level,
                ];
            }
        }

        if ($item->isDir()) {
            return $this->viewDirectory($item, $data);
        } else {
            return $this->viewFile($item, $data);
        }
    }

    /**
     * Build a directory listing for an Archive directory.
     */
    public function viewDirectory(ArchiveDirectoryInterface $item, array $data = [])
    {
        if (!empty($item->path())) {
        }
        $data['children'] = $item->children();

        return $this->render('archive/directory.html.twig', $data);
    }

    /**
     * Build a Archive file view.
     */
    public function viewFile(ArchiveFileInterface $item, array $data = [])
    {
        $data['page_title'] = $item->getTitle();
        $data['archive_content'] = $item->getBodyHtml();

        return $this->render('archive/file.html.twig', $data);
    }
}
