<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

abstract class AbstractService
{
    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var string */
    protected $formClassName;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(string $formClassName)
    {
        $this->formClassName = $formClassName;
    }

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param Object|array|null $data Data the form should be prefilled with (used for patch)
     * @param array $options
     * @return FormInterface
     */
    public function createForm($data = null, array $options = []) : FormInterface
    {
        return $this->formFactory->create($this->formClassName, $data, $options);
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function isPostValid(FormInterface $form) : bool
    {
        return $form->isValid();
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function isPatchValid(FormInterface $form) : bool
    {
        return $form->isValid();
    }

    public function flush()
    {
        $this->em->flush();
    }
}