<?php

namespace Alexartwww\Symfony\Controller;

use Alexartwww\Symfony\Entity\Category;
use Alexartwww\Symfony\Entity\Price;
use Alexartwww\Symfony\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PricesController
{
    #[Route('/api/prices', methods: ['GET', 'HEAD'])]
    public function list(ManagerRegistry $doctrine): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $prices = $doctrine->getRepository(Price::class)->findAll();

        $json = $serializer->serialize(
            $prices,
            JsonEncoder::FORMAT,
            [JsonEncode::OPTIONS => JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT]);

        return new Response(
            $json,
            200,
            ['Content-Type' => 'application/json']
        );
    }

    #[Route('/api/prices', methods: ['POST'])]
    public function createPrice(Request $request, ValidatorInterface $validator, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $body = $request->toArray();

        if (!isset($body["category_name"]) && !isset($body["category_id"])) {
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Body should contain category_name or category_id',
            ], 400, ['Content-Type' => 'application/json']);
        }

        if (!isset($body["product_name"]) && !isset($body["product_id"])) {
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Body should contain product_name or product_id',
            ], 400, ['Content-Type' => 'application/json']);
        }

        if (isset($body["category_id"])) {
            $category = $doctrine->getRepository(Category::class)->find(intval($body["category_id"]));
        } else {
            $category = $doctrine->getRepository(Category::class)->findOneByName($body["category_name"]);
        }

        if (!$category) {
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Category not found',
            ], 400, ['Content-Type' => 'application/json']);
        }

        if (isset($body["product_id"])) {
            $product = $doctrine->getRepository(Product::class)->find(intval($body["product_id"]));
        } else {
            $product = $doctrine->getRepository(Product::class)->findOneByName($body["product_name"]);
        }

        if (!$product && !isset($body["product_name"])) {
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Product not found and you did not specify product_name',
            ], 400, ['Content-Type' => 'application/json']);
        }

        if (!$product) {
            $product = (new Product())->setCategory($category)->setName($body["product_name"]);
            $entityManager->persist($product);
            $entityManager->flush();
        }

        $price = $doctrine->getRepository(Price::class)->findOneByProductVariantCurrency($product, $body["variant"], $body["currency"]);
        if ($price) {
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Price already exists',
            ], 400, ['Content-Type' => 'application/json']);
        }

        $price = new Price();
        $price->setProduct($product);
        $price->setVariant($body["variant"]);
        $price->setCurrency($body["currency"]);
        $price->setPrice(floatval($body["price"]));

        $errors = $validator->validate($price);
        if (count($errors) > 0) {
            return (new JsonResponse([
                'status' => 'error',
                'error' => (string)$errors,
            ], 400, ['Content-Type' => 'application/json']));
        }

        $entityManager->persist($price);
        $entityManager->flush();

        return new JsonResponse([
            'status' => 'ok',
            'id' => $price->getId(),
        ], 201);
    }
}
