<?php

namespace App\Service;

use App\Repository\CategoryRepository;

class CategoryService
{
    /**
     * @var CategoryRepository
     */
    protected $repository;

    /**
     * @return CategoryRepository
     */
    public function getRepository(): CategoryRepository
    {
        return $this->repository;
    }

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }
}