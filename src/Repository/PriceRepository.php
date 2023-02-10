<?php

namespace Alexartwww\Symfony\Repository;

use Alexartwww\Symfony\Entity\Price;
use Alexartwww\Symfony\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Price>
 *
 * @method Price|null find($id, $lockMode = null, $lockVersion = null)
 * @method Price|null findOneBy(array $criteria, array $orderBy = null)
 * @method Price[]    findAll()
 * @method Price[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }

    public function save(Price $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Price $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ?Price Returns Price object
     */
    public function findOneByProductVariantCurrency(Product $product, ?string $variant, ?string $currency): ?Price
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.product = :product AND p.variant = :variant AND p.currency = :currency')
            ->setParameter('product', $product->getId())
            ->setParameter('variant', $variant)
            ->setParameter('currency', $currency)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
