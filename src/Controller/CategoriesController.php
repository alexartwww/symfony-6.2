<?php

namespace Alexartwww\Symfony\Controller;

use Alexartwww\Symfony\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CategoriesController
{
    #[Route('/api/categories', methods: ['GET', 'HEAD'])]
    public function list(ManagerRegistry $doctrine): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $categories = $doctrine->getRepository(Category::class)->findAll();

        $json = $serializer->serialize(
            $categories,
            JsonEncoder::FORMAT,
            [JsonEncode::OPTIONS => JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT]);

        return new Response(
            $json,
            200,
            ['Content-Type' => 'application/json']
        );
    }
}
