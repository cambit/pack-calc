<?php

namespace App\Controller;

use App\Entity\Calculation;
use App\Model\Bins;
use App\Service\ProductHashService;
use App\Service\ThreeDBinPackingApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PackingCalculationController.
 */
class PackingCalculationController extends AbstractController
{
    public function calc(Request $request, ThreeDBinPackingApi $threeDBinPackingApi, ProductHashService $productHashService, Bins $bins): JsonResponse
    {
        $request_json = json_decode($request->getContent(), true);

        if (!is_array($request_json) or !isset($request_json['products'])) {
            throw new \ErrorException('Products array not provided');
        }
        $products = $request_json['products'];

        $sorted_products = $productHashService->sort($products);
        $hash = $productHashService->getHash($sorted_products);

        $repository = $this->getDoctrine()->getRepository(Calculation::class);
        $calculation = $repository->findOneBy([
            'hash' => $hash,
        ]);

        if ($calculation) {
            //check if products are same
            $results = array_diff(array_map('serialize', $calculation->getProducts()), array_map('serialize', $sorted_products));

            if (empty($results)) {
                $packing = $calculation->getBox();

                return $this->response($packing);
            }
        }

        $packing = $threeDBinPackingApi->calculate($products);

        $entityManager = $this->getDoctrine()->getManager();

        $calculation = new Calculation();
        $calculation->setHash($hash);
        $calculation->setBox($packing);
        $calculation->setProducts($sorted_products);
        $calculation->setAlert(isset($packing[$bins->getDefaultBinId()]));

        $entityManager->persist($calculation);
        $entityManager->flush();

        return $this->response($packing);
    }

    /**
     * Returns a JSON response.
     *
     * @param array<array|object> $data
     * @param int                 $status
     * @param array<string>       $headers
     *
     * @return JsonResponse
     */
    public function response(array $data, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }
}
