<?php

declare(strict_types = 1);

namespace App\Support;

/**
 * CollectionTransformer.
 */
final class CollectionTransformer
{
    /**
     * Transform a collection of objects to an array with multiple items.
     *
     * @param array $collection The object collection
     * @param int $total The total number of the collection
     *
     * @return array The list of object
     */
    public function transform(array $collection, int $total): array
    {
        $result = ['records' => [], 'total' => $total];

        foreach ($collection as $item) {
            $result['records'][] = $item->transform();
        }

        if (count($result['records']) == $total) {
            return $result['records'];
        }

        return $result;
    }
}
