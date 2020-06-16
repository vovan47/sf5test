<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class CategoryService extends AbstractService
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

    public function __construct(string $formClassName, CategoryRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
        parent::__construct($formClassName);
    }

    /**
     * @param FormInterface $form
     * @return Category
     */
    public function persist(FormInterface $form)
    {
        /** @var Category $category */
        $category = $form->getData();

        $this->em->persist($category);

        return $category;
    }
}