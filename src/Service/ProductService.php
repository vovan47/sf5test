<?php

namespace App\Service;

use App\Repository\ProductRepository;

class ProductService
{
    /**
     * @var ProductRepository
     */
    protected $repository;

    /**
     * @return ProductRepository
     */
    public function getRepository(): ProductRepository
    {
        return $this->repository;
    }

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }
}