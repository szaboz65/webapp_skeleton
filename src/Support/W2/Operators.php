<?php

declare(strict_types = 1);

namespace App\Support\W2;

/**
 * Operators for finder request.
 */
final class Operators
{
    public const EQ = 0; // IS =

    public const NE = 1; // !=

    public const GT = 2; // MORE >

    public const LT = 3; // LESS <

    public const GE = 4; // >=

    public const LE = 5; // <=

    public const BETWEEN = 6;

    public const BEGINS = 7;

    public const ENDS = 8;

    public const CONTAINS = 9;

    public const ISNULL = 10;

    public const ISNOTNULL = 11;

    public const INLIST = 12;

    public const NOTINLIST = 13;
}
