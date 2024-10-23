<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NoWrapParentheticals extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('noWrapParentheticals', [$this, 'addNoWrapHtml']),
        ];
    }

    public function addNoWrapHtml(string $input): string
    {
        return preg_replace('/\([^\)]+\)/', '<span style="white-space: nowrap">$0</span>', $input);
    }
}
