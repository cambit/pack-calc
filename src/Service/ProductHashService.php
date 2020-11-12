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
        $string = implode('-', array_map(function ($a) {
            return implode(' ', $a);
        }, $items));

        return sha1($string);
    }

    /**
     * @return array
     */
    public function sort(array $items)
    {
        usort($items, [$this, 'compare']);

        return $items;
    }


    /**
     * @param array $first
     * @param array $second
     * @return int
     */
    private function compare(array $first, array $second)
    {
        return $this->sum($first) <=> $this->sum($second);
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
