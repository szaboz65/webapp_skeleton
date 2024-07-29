<?php

declare(strict_types = 1);

namespace App\Support\W2;

/**
 * Search factory.
 */
final class SearchFactory
{
    /**
     * Create EQ search.
     *
     * @param string $field The field
     * @param int|string $value The value
     *
     * @return Search object
     */
    public static function createEQ(string $field, $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::EQ,
        ]);
    }

    /**
     * Create NE search.
     *
     * @param string $field The field
     * @param int|string $value The value
     *
     * @return Search object
     */
    public static function createNE(string $field, $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::NE,
        ]);
    }

    /**
     * Create GT search.
     *
     * @param string $field The field
     * @param int|string $value The value
     *
     * @return Search object
     */
    public static function createGT(string $field, $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::GT,
        ]);
    }

    /**
     * Create LT search.
     *
     * @param string $field The field
     * @param int|string $value The value
     *
     * @return Search object
     */
    public static function createLT(string $field, $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::LT,
        ]);
    }

    /**
     * Create GE search.
     *
     * @param string $field The field
     * @param int|string $value The value
     *
     * @return Search object
     */
    public static function createGE(string $field, $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::GE,
        ]);
    }

    /**
     * Create LE search.
     *
     * @param string $field The field
     * @param int|string $value The value
     *
     * @return Search object
     */
    public static function createLE(string $field, $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::LE,
        ]);
    }

    /**
     * Create BETWEEN search.
     *
     * @param string $field The field
     * @param int|string $value1 The value
     * @param int|string $value2 The value
     *
     * @return Search object
     */
    public static function createBetween(string $field, $value1, $value2): Search
    {
        return new Search([
            'field' => $field,
            'value' => [$value1, $value2],
            'operator' => Operators::BETWEEN,
        ]);
    }

    /**
     * Create BEGINS search.
     *
     * @param string $field The field
     * @param string $value The value
     *
     * @return Search object
     */
    public static function createBegins(string $field, string $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::BEGINS,
        ]);
    }

    /**
     * Create ENDS search.
     *
     * @param string $field The field
     * @param string $value The value
     *
     * @return Search object
     */
    public static function createEnds(string $field, string $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::ENDS,
        ]);
    }

    /**
     * Create CONTAINS search.
     *
     * @param string $field The field
     * @param string $value The value
     *
     * @return Search object
     */
    public static function createContains(string $field, string $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::CONTAINS,
        ]);
    }

    /**
     * Create IS NULL search.
     *
     * @param string $field The field
     *
     * @return Search object
     */
    public static function createIsNull(string $field): Search
    {
        return new Search([
            'field' => $field,
            'value' => null,
            'operator' => Operators::ISNULL,
        ]);
    }

    /**
     * Create IS NOT NULL search.
     *
     * @param string $field The field
     *
     * @return Search object
     */
    public static function createIsNotNull(string $field): Search
    {
        return new Search([
            'field' => $field,
            'value' => null,
            'operator' => Operators::ISNOTNULL,
        ]);
    }

    /**
     * Create INLIST search.
     *
     * @param string $field The field
     * @param array $value The value
     *
     * @return Search object
     */
    public static function createInList(string $field, array $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::INLIST,
        ]);
    }

    /**
     * Create NOTINLIST search.
     *
     * @param string $field The field
     * @param array $value The value
     *
     * @return Search object
     */
    public static function createNotInList(string $field, array $value): Search
    {
        return new Search([
            'field' => $field,
            'value' => $value,
            'operator' => Operators::NOTINLIST,
        ]);
    }
}
