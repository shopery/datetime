<?php

/**
 * This file is part of shopery/datetime
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\DateTime;

use DateTime as NativeDateTime;
use DateTimeInterface;

use Shopery\DateTime\Exception\AlreadyFrozenException;

/**
 * Class DateTime
 */
final class DateTime
{
    /** @var DateTimeInterface */
    private static $frozenTime;

    /**
     * Avoid creation of objects of this class by making this private
     */
    private function __construct()
    {
    }

    /**
     * Return current time.
     * Can be overriden with `self::freeze`.
     *
     * @return DateTimeInterface
     */
    public static function now()
    {
        return self::frozenTime() ?: new NativeDateTime();
    }

    /**
     * Create new date from formatted date string.
     * It can be relative to current time.
     *
     * @param string $time
     *
     * @return DateTime
     */
    public static function create($time)
    {
        $frozenTime = self::frozenTime();
        if ($frozenTime) {
            return $frozenTime->modify($time);
        }

        return new NativeDateTime($time);
    }

    /**
     * Allows to freeze time at current datetime or a given one.
     * This allows for easier testing.
     * Throws when multiple freezes to avoid bugs with testing order.
     *
     * @param DateTimeInterface|null $now
     *
     * @return DateTimeInterface Current frozen time
     *
     * @throws AlreadyFrozenException
     */
    public static function freeze(DateTimeInterface $now = null)
    {
        if (self::$frozenTime) {
            throw new AlreadyFrozenException();
        }

        return self::$frozenTime = $now ?: new NativeDateTime();
    }

    /**
     * Unfreezes time.
     * Can be safely called even when time is not frozen.
     *
     * @return DateTimeInterface Previously frozen time
     */
    public static function unfreeze()
    {
        $frozenTime = self::$frozenTime;
        self::$frozenTime = null;

        return $frozenTime;
    }

    /**
     * Fixes modification bugs when using \DateTime with `freeze`.
     *
     * @return DateTimeInterface
     */
    private static function frozenTime()
    {
        if (self::$frozenTime instanceof \DateTime) {
            return clone self::$frozenTime;
        }

        return self::$frozenTime;
    }
}
