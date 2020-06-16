<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\View as FOSView;
use FOS\RestBundle\Controller\Annotations;

class ProductController extends AbstractFOSRestController
{
    /**
     * @var ProductService
     */
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Annotations\Get("products")
     */
    public function getProductsAction()
    {
        $data = $this->productService->getRepository()->findAll();
        $view = $this->view($data, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Annotations\Get("products/{id}")
     *
     * @param Product $product
     *
     * @FOSView(serializerGroups={"app-product-default"})
     * @ParamConverter("product", class="App:Product")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getProductAction(Product $product)
    {
        $data = [
            'result' => $product,
        ];

        return View::create($data, Response::HTTP_OK);
    }
}
