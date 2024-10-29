<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * A helper to answer a 24-hour time string from an integer number of seconds.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class TimeHelper extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('secondsToTime', [$this, 'secondsToTime']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('secondsToTime', [$this, 'secondsToTime']),
        ];
    }

    /**
     * Answer a 24-hour time string from an integer number of seconds.
     *
     * @param int $seconds
     *                     Seconds since midnight
     *
     * @return string
     *                A string in am/pm format
     */
    public function secondsToTime($seconds)
    {
        $hour = floor($seconds / 3600);
        $minute = floor(($seconds - ($hour * 3600)) / 60);
        $hour = $hour % 24;

        if (!$hour) {
            $string = 12;
        } elseif ($hour < 13) {
            $string = $hour;
        } else {
            $string = $hour - 12;
        }

        $string .= ':'.str_pad($minute, 2, '0', STR_PAD_LEFT);

        if ($hour < 12) {
            $string .= ' am';
        } else {
            $string .= ' pm';
        }

        return $string;
    }
}
