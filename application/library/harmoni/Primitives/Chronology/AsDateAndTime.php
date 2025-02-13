<?php

/**
 * An interface that defines the protocol for converting to DateAndTime objects.
 */
interface AsDateAndTime
{
    /**
     * Answer a DateAndTime that represents this object.
     *
     * @return DateAndTime
     */
    public function asDateAndTime();
}
