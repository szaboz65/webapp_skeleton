<?php

declare(strict_types = 1);

namespace App\Support;

/**
 * Bin <-> array converter.
 */
final class BinArrayConverter
{
    /**
     * Make array from binary number based on the bits.
     *
     * @param int $roles The binary input
     *
     * @return array The result
     */
    public static function makeArrayFromBin(int $roles): array
    {
        $rolearray = [];
        for ($bit = 1; $bit < 32; $bit++) {
            if ($roles & (1 << ($bit - 1))) {
                $rolearray[] = $bit;
            }
        }

        return $rolearray;
    }

    /**
     * Make binary number from bitnumber array.
     *
     * @param array $rolearray The array of bitnumbers
     *
     * @throws \InvalidArgumentException
     *
     * @return int
     */
    public static function makeBinFromArray(array $rolearray): int
    {
        $roles = 0;
        foreach ($rolearray as $role) {
            if ($role > 32) {
                throw new \InvalidArgumentException();
            }
            $roles += (1 << ($role - 1));
        }

        return $roles;
    }
}
