<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{
    public function index(): JsonResponse
    {
        return new JsonResponse(['title' => 'Packaging Calculation API'], 200, []);
    }
}
