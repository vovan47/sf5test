<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        // Create 10 products
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setTitle(ucfirst($faker->word));
            $product->setPrice($faker->randomFloat());
            $product->setEid($faker->randomNumber());
            $manager->persist($product);
        }

        $manager->flush();
    }
}
