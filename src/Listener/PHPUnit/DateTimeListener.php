<?php

/**
 * This file is part of shopery/datetime
 *
 * Copyright (c) 2016 Shopery.com
 */

namespace Shopery\DateTime\Listener\PHPUnit;
use Shopery\DateTime\DateTime;

/**
 * Class DateTimeListener
 *
 * Adds a new annotation `@freezeTime` for PHPUnit testing.
 *
 * Tests marked as this will freezes time at start and unfreezes at the end.
 */
class DateTimeListener extends \PHPUnit_Framework_BaseTestListener
{
    /**
     * Annotation name.
     *
     * @var string
     */
    private $annotation;

    /**
     * Is this test frozen?
     *
     * @var bool
     */
    private $timeFrozen = false;

    /**
     * @param string $annotation Allows the annotation name to be configured.
     */
    public function __construct($annotation = 'freezeTime')
    {
        $this->annotation = $annotation;
    }

    /**
     * Listener for the start of a test.
     *
     * @param \PHPUnit_Framework_Test $test
     */
    public function startTest(\PHPUnit_Framework_Test $test)
    {
        $this->timeFrozen = false;

        if (!$test instanceof \PHPUnit_Framework_TestCase) {
            return;
        }

        $annotations = $test->getAnnotations();
        foreach ([ 'method', 'class' ] as $context) {
            if (!isset($annotations[$context][$this->annotation])) {
                continue;
            }

            list($time, ) = $annotations[$context][$this->annotation];

            $this->freezeTime($time);
            break;
        }
    }

    /**
     * Listener for the end of a test.
     *
     * @param \PHPUnit_Framework_Test $test
     * @param $time
     */
    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        if (!$this->timeFrozen) {
            return;
        }

        $this->unfreezeTime();
    }

    /**
     * Freezes time.
     *
     * @param $time
     */
    private function freezeTime($time)
    {
        if (null === $time) {
            $datetime = DateTime::now();
        } else {
            $datetime = DateTime::create($time);
        }

        $this->timeFrozen = DateTime::freeze($datetime);
    }

    /**
     * Unfreezes time.
     */
    private function unfreezeTime()
    {
        DateTime::unfreeze();
        $this->timeFrozen = null;
    }
}
