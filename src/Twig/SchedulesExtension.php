<?php

// src/Twig/AppExtension.php

namespace App\Twig;

use App\Schedule;
use App\Service\Schedules;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SchedulesExtension extends AbstractExtension
{
    /**
     * Create a new instance of this service.
     *
     * @param \App\Service\Osid\IdMap
     *   The IdMap service
     */
    public function __construct(
        private Schedules $schedules,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('scheduleHasCollisions', [$this, 'hasCollisions']),
            new TwigFunction('formatScheduleInfo', [$this, 'formatScheduleInfo']),
        ];
    }

    public function hasCollisions(Schedule $schedule, \osid_id_Id $offeringId): string
    {
        return $schedule->hasCollisions($offeringId);
    }

    public function formatScheduleInfo(string $scheduleInfo): string
    {
        $scheduleInfo = nl2br(strip_tags($scheduleInfo));
        $scheduleInfo = preg_replace('/\([^\)]+\)/', '<span style="white-space: nowrap">$0</span>', $scheduleInfo);

        return $scheduleInfo;
    }
}
