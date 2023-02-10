<?php

namespace Alexartwww\Symfony\Controller;

use Alexartwww\Symfony\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductsController
{
    #[Route('/api/products', methods: ['GET', 'HEAD'])]
    public function list(ManagerRegistry $doctrine): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $products = $doctrine->getRepository(Product::class)->findAll();

        $json = $serializer->serialize(
            $products,
            JsonEncoder::FORMAT,
            [JsonEncode::OPTIONS => JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT]);

        return new Response(
            $json,
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
