<?php

namespace App\Service;

/**
 * Class ProductHashService.
 */
class ProductHashService
{
    /**
     * @return string
     */
    public function getHash(array $items)
    {
        $items = $this->sort($items);
        $string = implode('-', array_map(function ($a) {
            return implode(' ', $a);
        }, $items));

        return sha1($string);
    }

    /**
     * @return array
     */
    private function sort(array $items)
    {
        usort($items, [$this, 'sum']);

        return $items;
    }

    /**
     * @return int|float
     */
    private function sum(array $array)
    {
        $sum = 0;
        foreach ($array as $a) {
            if (is_numeric($a)) {
                $sum += $a;
            }
        }

        return $sum;
    }
}
