<?php

/**
 * This file is part of shopery/datetime
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\DateTime\Listener\PHPUnit;

use DateTime as NativeDateTime;
use Shopery\DateTime\DateTime;

class DateTimeListenerTest extends \PHPUnit_Framework_TestCase
{
    const SLEEP_TIME = 2;

    /**
     * @freezeTime
     *
     * @group slow_test
     */
    public function test_annotation()
    {
        $expected = DateTime::now();
        sleep(self::SLEEP_TIME);

        $now = DateTime::now();

        $this->assertEquals($expected, $now);
    }

    /**
     * @freezeTime 2015-01-01
     */
    public function test_annotation_with_date()
    {
        $expected = new NativeDateTime('2015-01-01');
        $now = DateTime::now();

        $this->assertEquals($expected, $now);
    }

    /**
     * @freezeTime yesterday
     */
    public function test_annotation_with_relative_date()
    {
        $expected = new NativeDateTime('yesterday');
        $now = DateTime::now();

        $this->assertEquals($expected, $now);
    }
}
