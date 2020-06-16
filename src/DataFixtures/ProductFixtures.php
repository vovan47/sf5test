<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        // Create 10 categories
        $categoryIds = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setTitle(ucfirst($faker->word));
            $category->setEid($faker->randomNumber());
            $manager->persist($category);
            $manager->flush();

            $categoryIds[] = $category->getId();
        }

        $categoryRepository = $this->em->getRepository(Category::class);

        // Create 10 products
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setTitle(ucfirst($faker->word));
            $product->setPrice($faker->randomFloat());
            $product->setEid($faker->randomNumber());
            for ($j = 0; $j < 3; $j++) {
                $randomKey = array_rand($categoryIds);
                $product->addCategory($categoryRepository->find($categoryIds[$randomKey]));
            }
            $manager->persist($product);
        }

        $manager->flush();
    }
}
