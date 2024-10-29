<?php

// src/Twig/AppExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DateRangeFormatter extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('dateRangeToWeeks', [$this, 'dateRangeToWeeks']),
        ];
    }

    public function dateRangeToWeeks(\Date|\DateTime $start, \Date|\DateTime $end): int
    {
        return ceil(abs($end->format('U') - $start->format('U')) / 60 / 60 / 24 / 7);
    }
}
