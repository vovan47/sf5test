<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\View as FOSView;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;

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
     *
     * @FOSView(serializerGroups={"app-product-default", "app-category-default"})
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

    /**
     * @Annotations\Post("themes")
     *
     * @param Request $request
     *
     * @FOSView(serializerGroups={
     *     "app-product-default",
     *     "app-category-default",
     * })
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postAction(Request $request)
    {
        $handler = $this->productService;
        $form = $handler->createForm(null, [
            'http_method' => 'POST',
            'validation_groups' => ['POST']
        ]);
        $form->submit($request->request->all());
        if (!$handler->isPostValid($form)) {
            $this->throwRestUnprocessableFormException($form);
        }

        $theme = $handler->persist($form);
        $handler->flush();
        return $this->createRestPostResourceView($theme);
    }
}
