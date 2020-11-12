<?php

namespace App\Service;

use App\Model\Bins;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class ThreeDBinPackingApi.
 */
class ThreeDBinPackingApi
{
    const base_uri = 'http://eu.api.3dbinpacking.com';

    const url = '/packer/packIntoMany';

    const timeout = 2.0;

    /**
     * @var Client
     */
    private $client;

    private string $user_name;

    private string $key;

    private Bins $bins;

    /**
     * ThreeDBinPackingApi constructor.
     */
    public function __construct(string $user_name, string $key, Bins $bins)
    {
        $this->user_name = $user_name;
        $this->key = $key;

        $this->bins = $bins;

        $this->client = new Client(['base_uri' => self::base_uri, 'timeout' => self::timeout]);
        $this->client = new Client(['base_uri' => self::base_uri, 'timeout' => self::timeout]);
    }

    /**
     * @return array
     *
     * @throws ThreeDBinPackingApiError
     */
    public function calculate(array $items)
    {
        $data = [
            'bins' => $this->bins->getBins(),
            'items' => $items,
            'username' => $this->user_name,
            'api_key' => $this->key,
            'params' => $this->defaultParams(),
        ];

        $calculation = $this->get($data);

        return $this->processCalculation($calculation);
    }

    /**
     * @return mixed
     *
     * @throws ThreeDBinPackingApiError
     */
    private function get(array $data)
    {
        $headers = [
            'Content-type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
        ];

        try {
            $json_string = json_encode($data);
            if (false === $json_string) {
                throw new \ErrorException('Nelze prevest json');
            }

            $request = new \GuzzleHttp\Psr7\Request('POST', self::url, $headers, $json_string);
            $response = $this->client->send($request);

            if (200 == $response->getStatusCode()) {
                return json_decode($response->getBody()->getContents(), true);
            }
        } catch (GuzzleException $e) {
            //log
        }
        throw new ThreeDBinPackingApiError('3DBP API - Nepodařila se kalkulace');
    }

    /**
     * @return array
     */
    private function processCalculation(array $calculation)
    {
        $result = [];

        foreach ($calculation['response']['bins_packed'] as $package) {
            $items = [];
            foreach ($package['items'] as $item) {
                $items[] = $item['id'];
            }
            $result[$package['bin_data']['id']] = $items;
        }
        if (count($calculation['response']['not_packed_items']) > 0) {
            $items = [];
            foreach ($calculation['response']['not_packed_items'] as $not_packed_item) {
                $items[] = $not_packed_item['id'];
            }
            $result[$this->bins->getDefaultBinId()] = $items;
        }

        return $result;
    }

    /**
     * @return string[]
     */
    private function defaultParams()
    {
        return [
            'images_background_color' => '255,255,255',
            'images_bin_border_color' => '59,59,59',
            'images_bin_fill_color' => '230,230,230',
            'images_item_border_color' => '214,79,79',
            'images_item_fill_color' => '177,14,14',
            'images_item_back_border_color' => '215,103,103',
            'images_sbs_last_item_fill_color' => '99,93,93',
            'images_sbs_last_item_border_color' => '145,133,133',
            'images_width' => '100',
            'images_height' => '100',
            'images_source' => 'file',
            'images_sbs' => '1',
            'stats' => '1',
            'item_coordinates' => '1',
            'images_complete' => '1',
            'images_separated' => '1',
        ];
    }
}
