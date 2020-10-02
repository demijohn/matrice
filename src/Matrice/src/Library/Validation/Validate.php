<?php
declare(strict_types=1);

namespace Matrice\Library\Validation;

use Assert\Assert;
use Assert\LazyAssertion;

final class Validate extends Assert
{
    protected static $lazyAssertionExceptionClass = ValidationFailed::class;

    public static function lazy(): LazyAssertion
    {
        $lazyAssertion = new LazyAssertion();

        return $lazyAssertion
            ->setAssertClass(static::class)
            ->setExceptionClass(static::$lazyAssertionExceptionClass);
    }
}
