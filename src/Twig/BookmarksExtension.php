<?php

// src/Twig/AppExtension.php

namespace App\Twig;

use App\Service\Bookmarks;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BookmarksExtension extends AbstractExtension
{
    /**
     * Create a new instance of this service.
     *
     * @param \App\Service\Osid\IdMap
     *   The IdMap service
     */
    public function __construct(
        private Bookmarks $bookmarks,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isBookmarked', [$this, 'isBookmarked']),
        ];
    }

    public function isBookmarked(\osid_id_Id $courseId): string
    {
        return $this->bookmarks->isBookmarked($courseId);
    }
}
