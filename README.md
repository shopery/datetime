shopery/datetime
================

⚠ Note ⚠
--------
This library is not production ready yet, and can be subject to heavy changes.

Simple `DateTime` provider. Allows time freezing for testing purposes.

## Installation
You can install this library:

- Install via [composer](https://getcomposer.org): `composer require shopery/datetime`
- Use the [official Git repository](https://github.com/shopery/datetime): `git clone https://github.com/shopery/datetime`.

## Usage
Replace every `new DateTime()` with a call to `Shopery\DateTime\DateTime::now()` for better testability.

To create relative datetimes, be it in the past or the future, use `Shopery\DateTime\DateTime::create($timeString)` passing a [GNU string modificator](http://www.gnu.org/software/tar/manual/html_node/Date-input-formats.html).

## Freezing time
You can freeze time with the static method `DateTime::freeze()`. Pass any date as argument to set the current frozen time. This allows testing time-dependent scenarios.
You should remember to call `DateTime::unfreeze` at the end of the tests to clear it.

## Example

```php

use Shopery\DateTime\DateTime;
use DateTime as NativeDateTime;

class Coupon
{
    private $expiredTime;

    public function __construct(NativeDateTime $expiredTime)
    {
        $this->expiredTime = $expiredTime;
    }

    public function hasExpired()
    {
        return DateTime::now() > $this->expiredTime;
    }
}

class CouponTest
{
    public function test_has_expired()
    {
        $coupon = new Coupon(DateTime::create('yesterday'));

        $this->assertTrue($coupon->hasExpired());

        $frozenTime = DateTime::create('a month ago');
        DateTime::freeze($frozenTime);

        $this->assertFalse($coupon->hasExpired());

        DateTime::unfreeze();
    }
}
```

## PHPUnit Listener

You can setup PHPUnit to freeze time while entering some of your tests.
A PHPUnit listener is provided which adds a `freezeTime` annotation for your tests.
Here are the changes you need to do to your `phpunit.xml`:

```xml
<phpunit>
    ...
    <listeners>
        <listener class="Shopery\DateTime\Listener\PHPUnit\DateTimeListener" />
    </listeners>
    ...
</phpunit>
```

And now, you can add `@freezeTime` in the PHPDoc of each test.

```php
class MyTest
{
    /**
     * @freezeTime
     */
    public function test_frozen_in_current_time()
    {
    }

    /**
     * @freezeTime 2015-01-31 08:30:00
     */
    public function test_frozen_in_a_given_date()
    {
    }

    /**
     * @freezeTime first monday of January last year
     */
    public function test_frozen_in_a_relative_date()
    {
    }

    public function test_this_is_not_frozen()
    {
    }
}
```
