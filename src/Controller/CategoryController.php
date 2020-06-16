<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\CategoryService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\View as FOSView;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractFOSRestController
{
    /**
     * @var CategoryService
     */
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @Annotations\Get("categories")
     *
     * @FOSView(serializerGroups={"app-category-default"})
     */
    public function getCategoriesAction()
    {
        $data = $this->categoryService->getRepository()->findAll();
        $view = $this->view($data, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Annotations\Get("categories/{id}")
     *
     * @param Category $category
     *
     * @FOSView(serializerGroups={"app-category-default"})
     * @ParamConverter("category", class="App:Category")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getCategoryAction(Category $category)
    {
        $data = [
            'result' => $category,
        ];

        return View::create($data, Response::HTTP_OK);
    }
}