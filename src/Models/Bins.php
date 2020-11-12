<?php

namespace App\Model;

/**
 * Class Bins.
 */
class Bins
{
    /**
     * @var array|string[][]
     */
    private array $bins = [
            [
                'w' => '5',
                'h' => '5',
                'd' => '5',
                'max_wg' => '0',
                'id' => 'Bin1',
            ],
            [
                'w' => '3',
                'h' => '3',
                'd' => '3',
                'max_wg' => '0',
                'id' => 'Bin2',
            ],
        ];

    /**
     * @var array|string[]
     */
    private array $fallback_bin = [
        'w' => '50',
        'h' => '50',
        'd' => '50',
        'max_wg' => '0',
        'id' => 'FallBack',
    ];

    /**
     * @return array|string[][]
     */
    public function getBins()
    {
        return $this->bins;
    }

    /**
     * @return mixed|string
     */
    public function getDefaultBinId()
    {
        return $this->fallback_bin['id'];
    }
}
