<?php
namespace CleanPhp\Invoicer\Tests\Unit\Framework\Zf2\Service\Persistence\Hydrator\Strategy;

use Application\Persistence\Hydrator\Strategy\DateStrategy;

class DateStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Hydrate with a valid date string should return an datetime object
     */
    public function testHydrateWithAValidDateStringShouldReturnAnDatetimeObject()
    {
        $strategy = new DateStrategy();
        $value = '2017-01-01';
        $object = $strategy->hydrate($value);
        static::assertInstanceOf(\DateTime::class, $object);
        static::assertEquals($value, $object->format('Y-m-d'));
    }


    /**
     * Extract a date from a datetime object should return date string
     */
    public function testExtractADateFromADatetimeObjectShouldReturnDateString()
    {
        $strategy = new DateStrategy();
        $date = new \DateTime('2017-01-01');
        $dateString = $strategy->extract($date);
        static::assertEquals($date->format('Y-m-d'), $dateString);
    }
}

