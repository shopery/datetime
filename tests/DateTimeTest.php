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

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    const SLEEP_TIME = 2;

    public function tearDown()
    {
        parent::tearDown();

        // Just to be sure
        DateTime::unfreeze();
    }

    public function test_now_returns_current_time()
    {
        $expected = new NativeDateTime();

        $now = DateTime::now();

        $this->assertInstanceOf(DateTimeInterface::class, $now);
        $this->assertEquals($expected, $now);
    }

    public function test_create_allows_modification()
    {
        $expected = new NativeDateTime('-10 minutes');

        $now = DateTime::create('-10 minutes');

        $this->assertInstanceOf(DateTimeInterface::class, $now);
        $this->assertEquals($expected, $now);
    }

    public function test_allows_freezing()
    {
        $frozenTime = DateTime::freeze();

        $this->assertInstanceOf(DateTimeInterface::class, $frozenTime);
    }

    public function test_throws_when_freezing_already_frozen_time()
    {
        DateTime::freeze();
        // Order is important to check first freeze is not throwing
        $this->expectException(AlreadyFrozenException::class);

        DateTime::freeze();
    }

    /**
     * @group slow_test
     */
    public function test_allows_freezing_time_with_now()
    {
        $expected = new NativeDateTime();
        $this->freezeTimeAndWait();

        $now = DateTime::now();

        $this->assertEquals($expected, $now);
    }

    /**
     * @group slow_test
     */
    public function test_allows_freezing_time_with_create()
    {
        $expected = new NativeDateTime('-10 minutes');
        $this->freezeTimeAndWait();

        $now = DateTime::create('-10 minutes');

        $this->assertEquals($expected, $now);
    }

    /**
     * @group slow_test
     */
    public function test_allows_freezing_time_in_the_past()
    {
        $expected = new NativeDateTime('-10 minutes');
        $this->freezeTimeAndWait($expected);

        $now = DateTime::now();

        $this->assertEquals($expected, $now);
    }

    private function freezeTimeAndWait(DateTimeInterface $frozenTime = null)
    {
        $frozenTime = DateTime::freeze($frozenTime);
        sleep(self::SLEEP_TIME);

        return $frozenTime;
    }
}

