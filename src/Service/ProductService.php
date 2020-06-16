<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class ProductService extends AbstractService
{
    /**
     * @var ProductRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @return ProductRepository
     */
    public function getRepository(): ProductRepository
    {
        return $this->repository;
    }

    public function __construct(string $formClassName, ProductRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
        parent::__construct($formClassName);
    }

    /**
     * @param FormInterface $form
     * @return Product
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist(FormInterface $form)
    {
        /** @var Product $product */
        $product = $form->getData();

        $this->em->persist($product);

        return $product;
    }
}