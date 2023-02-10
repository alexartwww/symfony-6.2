<?php

namespace Alexartwww\Symfony\DataFixtures;

use Alexartwww\Symfony\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements ORMFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName("Shoes");
        $manager->persist($category);

        $category = new Category();
        $category->setName("Jewelry");
        $manager->persist($category);

        $manager->flush();
    }
}
