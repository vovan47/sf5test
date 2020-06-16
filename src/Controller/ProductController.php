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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @Annotations\Post("products")
     *
     * @param Request $request
     *
     * @FOSView(serializerGroups={
     *     "app-product-default",
     * })
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function postAction(Request $request)
    {
        $handler = $this->productService;
        $form = $handler->createForm(null, [
            'http_method' => 'POST',
        ]);
        $form->submit($request->request->all());
        if (!$handler->isPostValid($form)) {
            throw new \Exception('Input is not valid');
        }

        $product = $handler->persist($form);
        $handler->flush();

        $data = [
            'result' => $product,
        ];
        return View::create($data, Response::HTTP_CREATED);
    }

    /**
     * @Annotations\Patch("products/{id}")
     *
     * @param Request $request
     * @param Product $product
     *
     * @ParamConverter("Product", class="App:Product")
     *
     * @return \FOS\RestBundle\View\View
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function patchAction(Request $request, Product $product)
    {
        $handler = $this->productService;
        $form = $handler->createForm($product, [
            'http_method' => 'PATCH',
        ]);
        $form->submit($request->request->all(), false);
        if (!$handler->isPatchValid($form)) {
            throw new \Exception('Input data is not valid');
        }

        $handler->persist($form);
        $handler->flush();

        return View::create('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Annotations\Delete("products/{id}")
     *
     * @param Product $product
     *
     * @ParamConverter("Product", class="App:Product")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deleteAction(Product $product)
    {
        if (!$product instanceof Product) {
            throw new NotFoundHttpException();
        }

        $handler = $this->productService;
        $handler->getEm()->remove($product);
        $handler->getEm()->flush();

        return View::create('', Response::HTTP_NO_CONTENT);
    }
}
